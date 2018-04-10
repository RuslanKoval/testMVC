<?php
namespace app\services\user;


use app\models\UserModel;

class User
{
    private static $user = null;


    public static function getUser()
    {
        if (self::$user == null) {
            $user = new UserModel();

            if(isset($_SESSION['user'])) {
                $userId = $_SESSION['user'];
                $data = $user->getUserById($userId);
                $user->loadData($data);

                self::$user = $user;
            }
        }

        return self::$user;

    }
}