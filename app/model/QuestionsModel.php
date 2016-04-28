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

    public static function getAnswers($id)
    {
        $query = self::newQuery()
            ->select()
            ->from('answers')
            ->where(['question_id', '=', $id]);

        return $query->find();
    }

    public static function getComments($id)
    {
        $query = self::newQuery()
            ->select()
            ->from('question_comments')
            ->where(['question_id', '=', $id]);

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

    public static function count()
    {
        return self::newQuery()->select('COUNT(*) as count')->from(static::$table)->first()->count;
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('ORDER BY creation_date DESC LIMIT ' . $page
            * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }
}