<?php

namespace app\services\authorization;


use app\interfaces\UserInterface;

class Authorization
{
    const STATUS_OK = 1;

    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }


    public function checkPassword($password)
    {
        return ($this->user->getPassword() == $this->user->generateHash($password));
    }

    public function login()
    {
        if ($this->user->getStatus() == self::STATUS_OK) {
            $_SESSION['user'] = $this->user->getId();
        }
    }

    public static function logout()
    {
        unset($_SESSION['user']);
    }

}