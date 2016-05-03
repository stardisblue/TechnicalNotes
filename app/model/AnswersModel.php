<?php
/**
 * TechnicalNotes <https://www.github.com/stardisblue/TechnicalNotes>
 * Copyright (C) 2016  TechnicalNotes Team
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace techweb\app\model;

use rave\core\database\orm\Model;

class AnswersModel extends Model
{
    protected static $table = 'answers';

    public static function sortComments($id)
    {
        $array = static::getComments($id);

        $itemsById = [];
        foreach ($array as $item) {
            $itemsById[$item->id] = $item;
        }

        $itemsReversed = array_reverse($itemsById, true);
        $itemsTree = [];

        foreach ($itemsReversed as $item) {
            if ($item->parent_id) {
                $itemsReversed[$item->parent_id]->items[] = $item;
            } else {
                $itemsTree[] = $item;
            }
        }

        return $itemsTree;
    }

    public static function getComments($id)
    {
        $query = self::newQuery()
            ->select('answer_comments.*, users.username')
            ->from('answer_comments, users')
            ->where([
                'conditions' => 'answer_id = :answer_id AND user_id = users.id',
                'values' => [':answer_id' => $id]
            ]);

        return $query->find();
    }

    public static function getComment($id)
    {
        $query = self::newQuery()
            ->select()
            ->from('answer_comments')
            ->where(['id', '=', $id]);

        return $query->first();
    }

    public static function addComment($answer_id, $user_id, $parent_id, $content)
    {
        $query = self::newQuery()
            ->insertInto('answer_comments')
            ->values([
                'answer_id' => $answer_id,
                'user_id' => $user_id,
                'parent_id' => $parent_id,
                'content' => $content
            ]);

        $query->execute();
    }

    public static function deleteComment($comment_id)
    {
        $query = self::newQuery()
            ->delete()
            ->from('answer_comments')
            ->where(['id', '=', $comment_id]);

        $query->execute();
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('ORDER BY creation_date DESC LIMIT ' . $page
            * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }
}