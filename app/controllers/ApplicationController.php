<?php

namespace app\controllers;

use app\helpers\accessHelper;
use app\models\UserModel;
use app\services\authorization\Authorization;
use core\Controller;
use core\Model;


class ApplicationController extends Controller
{

    public function indexAction()
    {

    }
    /**
     *
     */
    public function loginAction()
    {
        AccessHelper::checkLoginUser();

        $user = new UserModel();
        $user->setScenario(Model::LOAD_SCENARIO);

        $this->view->status =  true;

        if ($this->loadData()) {


            $login = $this->getRequest()->getPost('login');
            $password =  $this->getRequest()->getPost('password');

            $data = $user->checkUserByName($login);

            if ($data) {
                if (!$user->validate($data)) {
                    $auth = new Authorization($user);

                    if ($auth->checkPassword($password)) {
                        $auth->login();

                        header("Location: /");
                    }
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
        AccessHelper::checkLogoutUser();

        Authorization::logout();
        header("Location: /");
    }
}