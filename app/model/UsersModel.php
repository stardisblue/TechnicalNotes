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
 *
 */

namespace techweb\app\model;

use rave\core\database\orm\Model;
use rave\core\database\orm\Query;

class UsersModel extends Model
{
    protected static $table = 'users';

    public static function userIsAdmin($id):bool
    {
        return Query::create()->select()->from(static::$table)->where(
            [
                'AND' => [
                    ['id', '=', $id],
                    ['isadmin', '=', 1]
                ]
            ])->first() ? true : false;
    }

    public static function userExistCheckByMail($email):bool
    {
        $query = Query::create()->select()->from('users')->where(['mail', '=', $email]);

        return $query->first() ? true : false;
    }

    public static function userExistCheckById($id):bool
    {
        $query = Query::create()->select()->from(static::$table)->where(['id', '=', $id]);

        return $query->first() ? true : false;
    }

}