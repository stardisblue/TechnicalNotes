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
use rave\lib\core\security\Auth;
use rave\lib\core\security\Password;
use techweb\app\model\UsersModel;

class AdminInterface extends AdminController
{
    public function index()
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index', ['success' => $success, 'warning' => $warning, 'info' => $info]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function login()
    {
        if (!In::isSetPost(['email', 'password', 'csrf'])) {
            $success = In::session('success');
            $info = In::session('info');
            $warning = In::session('warning');
            $this->loadView('login', ['warning' => $warning, 'info' => $info, 'success' => $success]);
            Out::unsetSession('success');
            Out::unsetSession('info');
            Out::unsetSession('warning');

            return;
        }

        $this->checkCSRF('admin/login');

        $user = UsersModel::getByEmail(In::post('email'));
        // check if the user is an admin
        if (!isset($user) || !UsersModel::userIsAdmin($user->id)) {
            $this->loadView('login', ['warning' => 'login_error', 'info' => null]);

            return;
        }

        // check if the password is the good one
        if (!Password::verify(In::post('password'), $user->password)) {
            $this->loadView('login', ['warning' => 'password_error', 'info' => null]);

            return;
        }

        // the user is an admin
        // the has this name :)
        Auth::login('admin');
        Out::session('login', $user->id);
        $this->redirect('admin/');
    }

    public function logout()
    {
        $this->checkCSRF('admin/', 'csrf', 'get');

        Out::unsetSession('admin');
        Out::unsetSession('login');
        Out::session('success', 'logout');
        $this->redirect('admin/');
    }
}