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

namespace techweb\app\controller;


use techweb\app\entity\UsersEntity;
use techweb\app\model\UsersModel;
use techweb\lib\core\io\In;

class UserManagement extends BackEndController
{
    public function list_users()
    {
        $user_model = new UsersModel();

        $this->loadView('listusers', ['users' => $user_model->find()]);
    }

    public function add_user()
    {
        if (In::isSetPost('name', 'firstname', 'password', 'mail')) {
            $user_entity = new UsersEntity();
            $user_entity->name = In::post('name');
            $user_entity->firstname = In::post('firstname');
            $user_entity->password = In::post('password');
            $user_entity->mail = In::post('mail');
            $user_entity->verification = 'test';


            $user_model = new UsersModel();
            if ($user_model->userExist($user_entity->mail)) {
                $message = 'User already registred with this name';
            } else {
                $user_model->save($user_entity);
                $message = 'utilisateur ajouté';
            }

            $this->loadView('useradd', ['message' => $message]);
        } else {
            $this->loadView('userregister');
        }
    }

    public
    function deleteUser($id)
    {

    }

    public
    function editUser($id)
    {

    }

    public
    function viewUser($id)
    {

    }
}