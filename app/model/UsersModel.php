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

use techweb\core\database\orm\Model;

class UsersModel extends Model
{
    protected static $table = 'users';

    public function userExistCheckByMail($email):bool
    {
        $this->newQuery()->select()->from($this)->where(['conditions' => ['mail', '=', $email]]);

        return $this->first() ? true : false;
    }

    public function userExistCheckById($id):bool
    {
        $this->newQuery()->select()->from($this)->where(['conditions' => ['id', '=', $id]]);

        return $this->first() ? true : false;
    }

    public function userIdentityById($email)
    {
        $this->newQuery()->select()->from($this)->where(['conditions' => ['id', '=', $id]]);
        return $this->first();
    }
}