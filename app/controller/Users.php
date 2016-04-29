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
use rave\lib\core\security\Random;
use rave\lib\core\security\Text;
use techweb\app\controller\abstracts\FrontEndController;
use techweb\app\entity\UsersEntity;
use techweb\app\model\QuestionsModel;
use techweb\app\model\TechnotesModel;
use techweb\app\model\UsersModel;

class Users extends FrontEndController
{
    public function index()
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index',
            [
                'count' => UsersModel::count(),
                'users' => UsersModel::all(),
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
        if (!isset($this->data['userLogged'])) {
            $user = new UsersEntity();
            if (In::isSetPost(['name', 'firstname', 'username', 'password', 'verifypassword', 'email'])) {
                $this->checkCSRF('');

                if (In::post('password') !== In::post('verifypassword')) {
                    $this->loadView('create', ['warning' => 'password']);

                    return;
                } elseif (!In::post('email', FILTER_SANITIZE_EMAIL)) {
                    $this->loadView('create', ['warning' => 'email']);

                    return;
                } elseif (!In::post('username')) {
                    $this->loadView('create', ['warning' => 'username']);

                    return;
                }

                $password = In::post('password');

                if (strlen($password) < 6) {
                    $this->loadView('create', ['warning' => 'password_length']);

                    return;
                }

                $user->name = Text::clean(In::post('name'));
                $user->firstname = Text::clean(In::post('firstname'));
                $user->email = Text::clean(In::post('email', FILTER_SANITIZE_EMAIL));
                $user->username = Text::clean(In::post('username'));

                //todo MAILS !!!!
                if (UsersModel::userExist($user)
                    /*&& !mail($user->email, '[Technotes] validez votre Compte',
                        'Voici le lien de validation, cliquez dessus afin de valider votre compte :\r\n' . WEB_ROOT
                        . '/validate?email=' . $user->email . '&token=' . $user->token)*/
                ) {
                    $this->loadView('create', ['user' => $user, 'info' => 'already']);
                } else {
                    $user->password = Password::hash($password);
                    $user->token = Random::sha1();

                    UsersModel::save($user);

                    $this->loadView('create_success', ['user' => $user]);
                }
            } else {
                $info = In::session('info');
                $warning = In::session('warning');
                $success = In::session('success');
                $this->loadView('create',
                    ['user' => $user, 'warning' => $warning, 'info' => $info, 'success' => $success]);
                Out::unsetSession('info');
                Out::unsetSession('warning');
                Out::unsetSession('success');
            }
        } else {
            $this->redirect('user/');
        }
    }

    public function view($id = null)
    {
        $user = UsersModel::get(['id' => $id ? $id : In::session('login')]);

        if (isset($user)) {
            $this->loadView('view',
                [
                    'user' => $user,
                    'questions' => QuestionsModel::getByUser($user->id),
                    'technotes' => TechnotesModel::getByUser($user->id),
                ]);
        } else {
            Out::session('info', 'no_user');
            $this->redirect('/users');
        }
    }

    public function update()
    {
        if (isset($this->data['userLogged'])) {
            $user = $this->data['userLogged'];

            if (In::isSetPost(['name', 'firstname', 'username'])) {

                $this->checkCSRF('users');

                $user->name = Text::clean(In::post('name'));
                $user->firstname = Text::clean(In::post('firstname'));

                $username = Text::clean(In::post('username'));

                if ($username !== $user->username && UsersModel::getByUsername($username)) {
                    $this->loadView('update', ['user' => $user, 'warning' => 'username_used']);

                    return;
                }

                $user->username = $username;

                UsersModel::save($user);
                $this->loadView('update', ['user' => $user, 'success' => 'updated']);
            } elseif (In::isSetPost(['password', 'verifypassword'])) {
                $this->checkCSRF('/users');

                if (In::post('password') !== In::post('verifypassword')) {
                    $this->loadView('update', ['user' => $user, 'warning' => 'password_check']);

                    return;
                }

                $password = In::post('password');

                if (strlen($password) < 6) {
                    $this->loadView('update', ['user' => $user, 'warning' => 'password_length']);

                    return;
                }

                $user->password = Password::hash(Text::clean(In::post('password')));
            } else {
                $success = In::session('success');
                $this->loadView('update',
                    ['user' => $user, 'success' => $success]);
                Out::unsetSession('success');
            }
        } else {
            $this->redirect('/login');
        }
    }

    public function login()
    {
        if (!isset($this->data['userLogged'])) {
            if (!In::isSetPost(['email', 'password'])) {
                $success = In::session('success');
                $info = In::session('info');
                $warning = In::session('warning');
                $this->loadView('login', ['warning' => $warning, 'info' => $info, 'success' => $success]);
                Out::unsetSession('success');
                Out::unsetSession('info');
                Out::unsetSession('warning');

                return;
            }

            $this->checkCSRF('login');

            $user = UsersModel::getByEmail(In::post('email'));

            if (!isset($user) || $user->token !== '') {
                $this->loadView('login', ['warning' => 'login_error', 'info' => null]);

                return;
            }

            // check if the password is the good one
            if (!Password::verify(In::post('password'), $user->password)) {
                $this->loadView('login', ['warning' => 'password_error', 'info' => null]);

                return;
            }

            if ($user->isadmin == true) {
                Auth::login('admin');
            }
            // the has this name :)
            Out::session('login', $user->id);
        }

        $this->redirect('user/');
    }

    public function validate()
    {
        $email = In::get('email', FILTER_SANITIZE_EMAIL);
        $token = In::get('token');
        if ($email && $token) {
            $user = UsersModel::getByEmail($email);

            if (isset($user)) {
                if ($user->token === '') {
                    Out::session('success', 'Compte déjà validé');
                    $this->redirect('login');
                } elseif ($user->token === $token) {
                    $user->token = '';

                    UsersModel::save($user);
                    Out::session('success', 'Compte validé');
                    $this->redirect('login');
                } else {
                    Out::session('warning', 'Token de validation incorrecte');
                    $this->redirect();
                }
            } else {
                Out::session('warning', 'Token de validation incorrecte');
                $this->redirect();
            }
        }
    }

    public function delete()
    {
        $this->checkCSRF('/user');
        if (isset($this->data['userLogged'])) {
            $user = $this->data['userLogged'];
            if (isset($user)) {
                UsersModel::delete($user);

                Out::session('success', 'deleted');
                $this->redirect('');
            }

            Out::session('warning', 'not_exist');
            $this->redirect('');
        }
    }

    public function logout()
    {
        $this->checkCSRF('', 'csrf', 'get');

        Out::unsetSession('admin');
        Out::unsetSession('login');
        Out::session('success', 'logout');
        $this->redirect('');
    }
}