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

class UsersModel extends Model
{
    protected static $table = 'users';

    public function userExistCheckByMail($email):bool
    {
        $query = $this->newQuery()->select()->from($this)->where(['mail', '=', $email]);

        return  $query->first() ? true : false;
    }

    public function userExistCheckById($id):bool
    {
        $query = $this->newQuery()->select()->from($this)->where(['id', '=', $id]);

        return  $query->first() ? true : false;
    }

    public function userIdentityById($id)
    {
        $query = $this->newQuery()->select()->from($this)->where(['id', '=', $id]);
/*
        [
        'OR' => [
            'AND' = >[
                ['id', '=', $id],
                ['id', '=', $id],
                ['id', '=', $id],
            ],
            ['title', '=', $title]]
              
        ]
*/
        return  $query->first();
    }
}