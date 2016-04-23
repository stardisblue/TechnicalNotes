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
use rave\lib\core\io\Out;
use rave\lib\core\security\Password;
use rave\lib\core\security\Text;
use techweb\app\entity\UsersEntity;
use techweb\app\model\UsersModel;

class AdminUsers extends AdminController
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index',
            [
                'users_count' => UsersModel::count(),
                'users' => UsersModel::page($page),
                'info' => $info,
                'warning' => $warning,
                'success' => $success
            ]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function create()
    {
        if (In::isSetPost(['name', 'firstname', 'password', 'verifypassword', 'email'])) {
            $this->checkCSRF('admin/users');

            if (In::post('password') !== In::post('verifypassword')) {
                $this->loadView('create', ['warning' => 'password']);

                return;
            } elseif (!In::post('email', FILTER_SANITIZE_EMAIL)) {
                $this->loadView('create', ['warning' => 'email']);

                return;
            }

            $password = In::post('password');

            if (strlen($password) < 6) {
                $this->loadView('create', ['warning' => 'password_length']);

                return;
            }

            $user_entity = new UsersEntity();
            $user_entity->name = Text::clean(In::post('name'));
            $user_entity->firstname = Text::clean(In::post('firstname'));
            $user_entity->password = Password::hash($password);
            $user_entity->email = Text::clean(In::post('email', FILTER_SANITIZE_EMAIL));
            $user_entity->token = ''; // pré-activé

            if (UsersModel::userExistCheckByEmail($user_entity->email)) {
                $warning = 'user_exist';
                $info = null;
            } else {
                UsersModel::add($user_entity);
                $info = 'user_added';
                $warning = null;
            }

            $this->loadView('create', ['warning' => $warning, 'info' => $info]);
        } else {
            $this->loadView('create');
        }
    }

    public function view($id)
    {
        $user = UsersModel::get(['id' => $id]);

        if (isset($user)) {
            $this->loadView('view', ['user' => $user]);
        } else {
            Out::session('info', 'no_user');
            $this->redirect('admin/users');
        }
    }

    public function update($id)
    {
        $user_entity = UsersModel::get(['id' => $id]);
        if (!isset($user_entity)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/users');
        }

        if (In::isSetPost(['name', 'firstname', 'email'])) {

            $this->checkCSRF('admin/users');

            $user_entity->name = Text::clean(In::post('name'));
            $user_entity->firstname = Text::clean(In::post('firstname'));
            $user_entity->email = Text::clean(In::post('email'));

            UsersModel::save($user_entity);
            $this->loadView('update', ['user' => $user_entity, 'success' => 'updated']);
        } elseif (In::isSetPost(['password', 'verifypassword', 'csrf'])) {
            $this->checkCSRF('admin/users');

            if (In::post('password') !== In::post('verifypassword')) {
                $this->loadView('update', ['user' => $user_entity, 'warning' => 'password_check']);

                return;
            }

            $password = In::post('password');

            if (strlen($password) < 6) {
                $this->loadView('update', ['user' => $user_entity, 'warning' => 'password_length']);

                return;
            }

            $user_entity->password = Password::hash(Text::clean(In::post('password')));
        } else {
            $success = In::session('success');
            $this->loadView('update',
                ['user' => $user_entity, 'success' => $success]);
            Out::unsetSession('success');
        }
    }

    public function delete($id)
    {
        $this->checkCSRF('admin/users');

        $user = UsersModel::get(['id' => $id]);
        if (isset($user)) {
            UsersModel::delete($user);

            Out::session('success', 'deleted');
            $this->redirect('admin/users');
        }

        Out::session('warning', 'not_exist');
        $this->redirect('admin/users');

    }

    public function validate($id)
    {
        $this->checkCSRF('admin/users');

        $user_entity = UsersModel::get(['id' => $id]);

        if (!isset($user_entity)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/users');
        }

        if ($user_entity->token === '') {
            Out::session('info', 'token_already');
            $this->redirect('admin/users');
        }

        $user_entity->token = '';

        UsersModel::save($user_entity);

        Out::session('success', 'token_validated');
        $this->redirect('admin/users');
    }

    public function upgrade($id)
    {
        $this->checkCSRF('admin/users');

        $user_entity = UsersModel::get(['id' => $id]);

        if (!isset($user_entity)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/users');
        }

        if ($user_entity->isadmin === '1') {
            Out::session('info', 'admin_already');
            $this->redirect('admin/users');
        }

        $user_entity->isadmin = 1;
        var_dump($user_entity);

        UsersModel::save($user_entity);

        Out::session('success', 'user_upgraded');
        $this->redirect('admin/users');
    }

    public function downgrade($id)
    {
        $this->checkCSRF('admin/users');

        $user_entity = UsersModel::get(['id' => $id]);

        if (!isset($user_entity)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/users');
        }

        if ($user_entity->isadmin === '0') {
            Out::session('info', 'admin_not_already');
            $this->redirect('admin/users');
        }

        $user_entity->isadmin = 0;
        var_dump($user_entity);

        UsersModel::save($user_entity);

        Out::session('success', 'user_downgraded');
        $this->redirect('admin/users');
    }
}