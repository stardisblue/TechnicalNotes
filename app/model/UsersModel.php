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

use rave\core\database\orm\Entity;
use rave\core\database\orm\Model;
use rave\core\database\orm\Query;

class UsersModel extends Model
{
    protected static $table = 'users';

    public static function userIsAdmin($id)
    {
        return Query::create()->select()->from(static::$table)->where(
            [
                'AND' => [
                    ['id', '=', $id],
                    ['isadmin', '=', 1]
                ]
            ])->first() ? true : false;
    }

    /**
     * @param $email
     * @return null|Entity
     * @throws \rave\core\exception\EntityException
     * @throws \rave\core\exception\IncorrectQueryException
     */
    public static function getByEmail($email)
    {
        $user = Query::create()->select()->from(static::$table)->where(['email', '=', $email])
            ->first(static::getEntityName());

        return $user;
    }

    public static function searchByEmail($email, $page = 0, $pagination = PAGINATION)
    {
        $user = Query::create()
            ->select('id, email')
            ->from(static::$table)
            ->where(['email', 'LIKE', '%' . $email . '%'])
            ->appendSQL('LIMIT ' . $page * $pagination . ',' . $pagination)
            ->find();

        return $user;
    }

    public static function userExistCheckByEmail($email)
    {
        $query = Query::create()->select()->from(static::$table)->where(['email', '=', $email]);

        return $query->first() ? true : false;
    }

    public static function userExistCheckById($id)
    {
        $query = Query::create()->select()->from(static::$table)->where(['id', '=', $id]);

        return $query->first() ? true : false;
    }

    public static function checkTokenByEmail($email)
    {
        return Query::create()
            ->select('token')
            ->from(static::$table)
            ->where(['email', '=', $email])->first()->token
        === '';

    }

    public static function checkTokenById($id)
    {
        return Query::create()
            ->select('token')
            ->from(static::$table)
            ->where(['id', '=', $email])->first()->token
        === '';

    }

    public static function count()
    {
        return self::newQuery()->select('COUNT(*) as count')->from(static::$table)->first()->count;
    }

    public static function page($page = 0, $pagination = PAGINATION)
    {
        return self::newQuery()->select()->from(static::$table)->appendSQL('LIMIT ' . $page * $pagination . ','
            . $pagination)->find(null, static::getEntityName());
    }
}