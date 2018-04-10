<?php

namespace app\controllers;

use app\models\UserModel;
use app\services\mailer\Mailer;
use app\services\user\User;
use core\Controller;
use core\Register;

class UserController  extends Controller
{

    /**
     *
     */
    public function registerAction()
    {
        $this->checkLoginUser();

        $user = new UserModel();

        if ($this->loadData()) {
            $user = new UserModel();
            $user->setUsername(Register::getField('username'));
            $user->setPassword(Register::getField('password'));
            $user->setTempPassword(Register::getField('password'));
            $user->setEmail(Register::getField('email'));
            $user->setConfirmPassword(Register::getField('confirm_password'));


            $checkData = $user->checkData();

            if ($checkData['success']) {
                $user->register();
                Mailer::sendConfirmMail($user->getUsername(), $user->getEmail(), $user->getConfirmToken());
                header("Location: /confirm");
            } else {
                $this->view->error = $checkData;

            }
        }
        $this->view->user = $user;
    }


    /**
     *
     */
    public function confirmAction()
    {
        $this->checkLoginUser();

        $user = new UserModel();
        $status = false;

        if ($this->loadData()) {
            $token = Register::getField('token');
            if ($token != '') {
                $data = $user->getUserByConfirmToken($token);
                if ($data) {
                    $user->loadData($data);
                    $user->setStatus(UserModel::STATUS_OK);
                    $user->setConfirmToken('');

                    if ($user->saveModel()) {
                        $status = true;
                    }
                }
            }

            $this->view->status = $status;

        }

    }

    /**
     *
     */
    public function loginAction()
    {
        $this->checkLoginUser();
        $user = new UserModel();

        $this->view->status =  true;

        if ($this->loadData()) {


            $login =  Register::getField('login');
            $password =  Register::getField('password');

            $data = $user->checkUserByName($login);

            if ($data) {
                $user->loadData($data);

                if ($user->checkPassword($password)) {
                    $user->login();
                    header("Location: /");
                }
            }

            $this->view->status = false;

        }

    }

    /**
     *
     */
    public function logoutAction()
    {
        $this->checkLogoutUser();

        $user = User::getUser();
        $user->logout();
        header("Location: /");
    }

    /**
     *
     */
    private function checkLoginUser()
    {
        $user = User::getUser();

        if ($user) {
            header("Location: /");
        }

    }

    /**
     *
     */
    private function checkLogoutUser()
    {
        $user = User::getUser();

        if (!$user) {
            header("Location: /");
        }

    }
}