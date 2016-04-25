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

class TagsModel extends Model
{
    protected static $table = 'tags';

    public static function tagExist($word)
    {
        if (self::getByWord($word) || TagsRefusedModel::getByWord($word) || TagsProposedModel::getByWord($word)) {
            return true;
        }

        return false;
    }

    public static function getByWord($word)
    {
        $query = self::newQuery()->select()->from(static::$table)->where(['word', '=', $word]);

        return $query->first();
    }

    public static function count()
    {
        return self::newQuery()->select('COUNT(*) as count')->from(static::$table)->first()->count;
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('ORDER BY word ASC LIMIT ' . $page
            * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }
}