<?php

namespace techweb\app\controller;

use rave\lib\core\io\In;
use rave\lib\core\security\Password;
use techweb\app\entity\UsersEntity;
use techweb\app\model\UsersModel;

class UserManagement extends BackEndController
{
    public function listUsers()
    {
        $user_model = new UsersModel();

        $this->loadView('listusers', ['users' => $user_model->all()]);
    }

    public function addUser()
    {
        if (In::isSetPost(['name', 'firstname', 'password', 'verifypassword', 'mail'])) {
            if (In::post('password') !== In::post('verifypassword')) {
                // todo
                return;
            }

            if (!In::post('mail', FILTER_SANITIZE_EMAIL)) {
                // todo
            }

            $user_entity = new UsersEntity();
            $user_entity->name = In::post('name');
            $user_entity->firstname = In::post('firstname');
            $user_entity->password = Password::hash(In::post('password'));
            $user_entity->mail = In::post('mail', FILTER_SANITIZE_EMAIL);
            $user_entity->verification = 'test';

            $user_model = new UsersModel();
            if ($user_model->userExistCheckByMail($user_entity->mail)) {
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

    public function getUser($id)
    {
        $userModel = new UsersModel();
        $userModel->get($id);

    }

    public function deleteUser($id)
    {

    }
}