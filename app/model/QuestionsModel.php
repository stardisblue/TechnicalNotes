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
use techweb\app\model\interfaces\QuestionTechnotesModelInterface;

class QuestionsModel extends Model implements QuestionTechnotesModelInterface
{
    protected static $table = 'questions';

    public static function getAnswersComments($id)
    {
        $array = static::getAnswers($id);

        foreach ($array as $key => $answer) {
            $array[$key]->comments = AnswersModel::sortComments($answer->id);
        }

        return $array;
    }

    public static function getAnswers($id)
    {
        $query = self::newQuery()
            ->select('answers.*, users.username')
            ->from('answers, users')
            ->where([
                'conditions' => 'question_id = :id AND user_id = users.id',
                'values' => [':id' => $id]
            ]);

        return $query->find();
    }

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
            ->select('question_comments.*, users.username')
            ->from('question_comments, users')
            ->where([
                'conditions' => 'question_id = :question_id AND user_id = users.id',
                'values' => [':question_id' => $id]
            ]);

        return $query->find();

    }

    public static function getTags($id)
    {
        return self::newQuery()
            ->select('tags.*')
            ->from(['questions_tags', 'tags'])
            ->where([
                'conditions' => 'tag_id = tags.id AND question_id = :id',
                'values' => [':id' => $id]
            ])
            ->find();

    }

    public static function addTag($id, $tag_id)
    {
        $query = self::newQuery()
            ->insertInto('questions_tags')
            ->values(['question_id' => $id, 'tag_id' => $tag_id]);

        return $query->execute();
    }

    public static function removeTag($id, $tag_id)
    {
        $query = self::newQuery()
            ->delete()
            ->from('questions_tags')
            ->where([
                'AND' => [
                    ['tag_id', '=', $tag_id],
                    ['question_id', '=', $id]
                ]
            ]);

        return $query->execute();
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('ORDER BY creation_date DESC LIMIT ' . $page
            * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }

    public static function getByUser($id)
    {
        $query = self::newQuery()
            ->select()
            ->from(static::$table)
            ->where(['user_id', '=', $id]);

        return $query->find(null, static::getEntityName());
    }

    public static function addComment($question_id, $user_id, $parent_id, $content)
    {
        $query = self::newQuery()
            ->insertInto('question_comments')
            ->values([
                'question_id' => $question_id,
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
            ->from('question_comments')
            ->where(['id', '=', $comment_id]);

        $query->execute();
    }

    public static function getComment($id)
    {
        $query = self::newQuery()
            ->select()
            ->from('question_comments')
            ->where(['id', '=', $id]);

        return $query->first();
    }
}