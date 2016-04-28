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

class AnwsersModel extends Model
{
    protected static $table = 'anwsers';

    public static function sortComments($id)
    {
        $array = static::getComments($id);

        foreach ($array as $item) {
            $itemsById[$item->id] = $item;
        }

        $itemsReversed = array_reverse(sort($itemsById));
        $itemsTree = [];
        foreach ($itemsReversed as $item) {
            if ($item->parent_id) {
                $itemsReversed[$item->parent_id]->items = $item;
            } else {
                $itemsTree[] = $item;
            }
        }

        return $itemsTree;
    }

    public static function getComments($id)
    {
        return self::newQuery()->select()->from('answer_comments')->where(['answer_id', '=', $id]);
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('ORDER BY creation_date DESC LIMIT ' . $page
            * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }
}