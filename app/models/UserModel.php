<?php

namespace app\models;
use app\interfaces\UserInterface;
use core\Model;
use Respect\Validation\Validator;

class UserInterfaceModel extends Model implements UserInterface
{

    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $tempPassword;
    protected $confirmPassword;
    protected $status;
    protected $created_at;
    protected $confirm_token;

    const STATUS_OK = 1;
    const STATUS_NOT_CONFIRM = 0;
    const MIN_PASSWORD_LENGTH = 6;


    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
    }

    /**
     * @param string $password
     * @return string
     */
    private function generateHash($password = '')
    {
        if(!$password)
            $password = $this->password;

        return md5($password);
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $password
     */
    public function setTempPassword($password)
    {
        $this->tempPassword = $password;
    }

    /**
     * @param mixed $confirmPassword
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status= $status;
    }

    /**
     * @param mixed $time
     */
    public function setCreated($time)
    {
        $this->created_at= $time;
    }

    /**
     * @param mixed $token
     */
    public function setConfirmToken($token)
    {
        $this->confirm_token = $token;
    }


    /**
     * @return $this->id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return $this->username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return $this->email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return $this->password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return $this->password
     */
    public function getTempPassword()
    {
        return $this->tempPassword;
    }

    /**
     * @return $this->confirmPassword
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * @return $this->status
     */
    public function getStatus()
    {
        return $this->status;

    }

    /**
     * @return $this->created_at
     */
    public function getCreated()
    {
        return $this->created_at;

    }

    /**
     * @return $this->confirm_token
     */
    public function getConfirmToken()
    {
        return $this->confirm_token;

    }


    /**
     * @return mixed
     */
    public function checkData()
    {
        $this->createTable();

        $errorArray['success'] = false;

        if (!Validator::stringType()->notEmpty()->validate($this->username)) {
            $errorArray['error']['username'] = "username is required";
        }

        if ($this->checkUserByName($this->username)) {
            $errorArray['error']['username'] = "username is busy";
        }

        if(!Validator::email()->validate($this->email)) {
            $errorArray['error']['email'] = "email is required";
        }

        if ($this->checkUserByEmail($this->email)) {
            $errorArray['error']['email'] = "email is busy";
        }

        if (!Validator::stringType()->notEmpty()->validate($this->password)) {
            $errorArray['error']['password'] = "password is required";
        }

        if (!Validator::stringType()->length(self::MIN_PASSWORD_LENGTH, null)->validate($this->password)) {
            $errorArray['error']['password'] = "Min password length is ".self::MIN_PASSWORD_LENGTH;
        }

        if ($this->password != $this->confirmPassword) {
            $errorArray['error']['confirmPassword'] = "password is not confirm";
        }



        if($errorArray['error'] == '') {
            $errorArray['success'] = true;
        }

        return $errorArray;
    }


    /**
     * @return bool|string
     */
    public function register()
    {
        $this->setCreated(time());
        $this->setConfirmToken($this->randomString(45));
        $this->setPassword($this->generateHash());
        $this->setStatus(self::STATUS_NOT_CONFIRM);

        $this->saveModel();
    }

    public function saveModel()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->created_at,
            'confirm_token' => $this->confirm_token,
            'status' => $this->status
        ];

        return $this->save($data);
    }

    protected function createTable(){
        $this->db->exec('create table if not exists `users` (
                                    `id` INTEGER PRIMARY KEY,
                                    `name` VARCHAR(255),
                                    `password` VARCHAR (255),
                                    `email` VARCHAR (255),
                                    `created_at` VARCHAR (12),
                                    `confirm_token` VARCHAR (255),
                                    `status` VARCHAR (10) )');

    }

    /**
     * @param $name
     * @return array
     */
    public function checkUserByName($name)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE name = "'.$name.'" LIMIT 1';

        $result = $this->db->query($query);

        return $result->fetchArray();
    }

    /**
     * @param $email
     * @return array
     */
    public function checkUserByEmail($email)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE email = "'.$email.'"  LIMIT 1';

        $result = $this->db->query($query);

        return $result->fetchArray();
    }


    /**
     * @param $token
     * @return array
     */
    public function getUserByConfirmToken($token)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE confirm_token = "'.$token.'" LIMIT 1';

        $result = $this->db->query($query);

        return $result->fetchArray();
    }


    /**
     * @param $id
     * @return array
     */
    public function getUserById($id)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE id = "'.$id.'" LIMIT 1';

        $result = $this->db->query($query);

        return $result->fetchArray();
    }

    public function loadData($data)
    {
        $this->setId($data['id']);
        $this->setUsername($data['name']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setStatus($data['status']);
        $this->setCreated($data['created_at']);
        $this->setConfirmToken($data['confirm_token']);
    }

    public function checkPassword($password)
    {
        return ($this->password == $this->generateHash($password));
    }

    public function login()
    {
        if ($this->status == self::STATUS_OK) {
            $_SESSION['user'] = $this->id;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}