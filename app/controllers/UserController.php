<?php

namespace app\controllers;

use app\helpers\accessHelper;
use app\models\UserModel;
use app\services\mailer\Mailer;
use app\services\user\User;
use core\Controller;
use core\Model;

class UserController  extends Controller
{

    /**
     *
     */
    public function registerAction()
    {
        AccessHelper::checkLoginUser();

        $user = new UserModel();
        $user->setScenario(Model::CREATE_SCENARIO);

        if ($this->loadData()) {

            $data = [
              'username' => $this->getRequest()->getPost('username'),
              'email' => $this->getRequest()->getPost('email'),
              'password' => $this->getRequest()->getPost('password'),
              'confirmPassword' => $this->getRequest()->getPost('confirmPassword'),
            ];

            if (!$user->validate($data)) {
                $user->register();
                Mailer::sendConfirmMail($user->getUsername(), $user->getEmail(), $user->getConfirmToken());
                header("Location: /confirm");
            }

            $this->view->error = $user->errors;
            $this->view->entity = $data;

        }

        $this->view->user = $user;
    }


    /**
     *
     */
    public function confirmAction()
    {
        AccessHelper::checkLoginUser();

        $user = new UserModel();
        $user->setScenario(Model::LOAD_SCENARIO);
        $status = false;

        if ($this->loadData()) {
            $token = $this->getRequest()->getParam('token');
            if ($token != '') {
                $data = $user->getUserByConfirmToken($token);
                if ($data) {
                    if (!$user->validate($data)) {
                        $user->setStatus(UserModel::STATUS_OK);
                        $user->setConfirmToken('');
                        if ($user->saveModel()) {
                            $status = true;
                        }
                    }
                }
            }

            $this->view->status = $status;

        }

    }

    public function profileAction()
    {
        AccessHelper::checkLogoutUser();

        $user = User::getUser();
        $this->view->user = $user;

    }

    public function editAction()
    {
        AccessHelper::checkLogoutUser();

        $user = User::getUser();
        $user->setScenario(Model::EDIT_SCENARIO);

        $data = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => '',
            'confirmPassword' => '',
            'created_at' => $user->getCreated(),
            'status' => $user->getStatus()
        ];

        if ($this->loadData()) {

            $data['username'] = $this->getRequest()->getPost('username');
            $data['email'] = $this->getRequest()->getPost('email');
            $data['password'] = $this->getRequest()->getPost('password');
            $data['confirmPassword'] = $this->getRequest()->getPost('confirmPassword');


            if (!$user->validate($data)) {
                $user->saveModel();
                header("Location: /profile");
            }
        }

        $this->view->entity = $data;
        $this->view->error = $user->errors;
    }


}