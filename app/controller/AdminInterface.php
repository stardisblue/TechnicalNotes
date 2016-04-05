<?php

namespace techweb\app\controller;

use rave\lib\core\io\In;
use rave\lib\core\io\Out;
use rave\lib\core\security\Auth;
use rave\lib\core\security\Password;
use techweb\app\model\UsersModel;

class AdminInterface extends BackEndController
{
    public function login()
    {
        if (!In::isSetPost(['login', 'password'])) {

            $info = In::session('login_info');
            $this->loadView('login_form', ["info" => $info]);
            Out::unsetSession('login_info');
            exit;
        }

        if (!UsersModel::userIsAdmin(In::post('login'))) {
            Out::session('login_info', 'login_error');
            $this->redirect('admin/');
        }

        $adminModel = new UsersModel();

        $admin = $adminModel->get(In::post('login'));

        if (!Password::verify(In::post('password'), $admin->admin_password)) {
            Out::session('login_info', 'password_error');
            $this->redirect('admin/');
        }

        Auth::login('admin');
        Out::session('login', $admin->admin_login);

        $this->redirect('admin/manage');
    }
}