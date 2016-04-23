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

namespace techweb\app\controller;

use rave\lib\core\io\In;
use rave\lib\core\security\Password;
use techweb\app\entity\UsersEntity;
use techweb\app\model\UsersModel;

class Users extends FrontEndController
{
    public function addUser()
    {
        if (In::isSetPost(['name', 'firstname', 'password', 'verifypassword', 'mail'])) {
            if (In::post('password') !== In::post('verifypassword')) {

                $this->redirect('/admin/user/add');

                return;
            } elseif (!In::post('mail', FILTER_SANITIZE_EMAIL)) {
                $this->redirect('/admin/user/add');

                return;
            }

            $user_entity = new UsersEntity();
            $user_entity->name = In::post('name');
            $user_entity->firstname = In::post('firstname');
            $user_entity->password = Password::hash(In::post('password'));
            $user_entity->mail = In::post('mail', FILTER_SANITIZE_EMAIL);
            $user_entity->verification = 'test';

            if (UsersModel::userExistCheckByEmail($user_entity->mail)) {
                $message = 'User already registred with this name';
            } else {
                UsersModel::save($user_entity);
                $message = 'utilisateur ajouté';
            }

            $this->loadView('useradd', ['message' => $message]);
        } else {
            $this->loadView('userregister');
        }
    }
}