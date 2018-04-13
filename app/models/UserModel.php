<?php

namespace app\models;
use app\interfaces\UserInterface;
use core\Model;
use Respect\Validation\Validator;

class UserModel extends Model implements UserInterface
{

    private $id;
    private $username;
    private $email;
    private $password;
    private $confirmPassword;
    private $status;
    private $created_at;
    private $confirm_token;

    public $errors = [];

    const STATUS_OK = '1';
    const STATUS_NOT_CONFIRM = '0';
    const MIN_PASSWORD_LENGTH = '6';

    private $statusList = [
      self::STATUS_OK,
      self::STATUS_NOT_CONFIRM
    ];


    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
    }

    /**
     * @param string $password
     * @return string
     */
    public function generateHash($password = '')
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


    public function validate($data)
    {
        $this->createTable();

        if($this->scenario == Model::CREATE_SCENARIO) {
            if (!Validator::stringType()->notEmpty()->validate($data['username'])) {
                $this->errors['username'] = "username is required";
            }

            if ($this->checkUserByName($data['username'])) {
                $this->errors['username'] = "username is busy";
            }

            if(!Validator::email()->validate($data['email'])) {
                $this->errors['email'] = "email is required";
            }

            if ($this->checkUserByEmail($data['email'])) {
                $this->errors['email'] = "email is busy";
            }

            if (!Validator::stringType()->notEmpty()->validate($data['password'])) {
                $this->errors['password'] = "password is required";
            }

            if (!Validator::stringType()->length(self::MIN_PASSWORD_LENGTH, null)->validate($data['password'])) {
                $this->errors['password'] = "Min password length is ".self::MIN_PASSWORD_LENGTH;
            }

            if ($data['password'] != $data['confirmPassword']) {
                $this->errors['confirmPassword'] = "password is not confirm";
            }

        } elseif ($this->scenario == Model::LOAD_SCENARIO) {
            if (!Validator::stringType()->notEmpty()->validate($data['username'])) {
                $this->errors['username'] = "username is required";
            }

            if(!Validator::email()->validate($data['email'])) {
                $this->errors['email'] = "email is required";
            }

            if (!Validator::stringType()->notEmpty()->validate($data['password'])) {
                $this->errors['password'] = "password is required";
            }

            if (!Validator::stringType()->notEmpty()->validate($data['created_at'])) {
                $this->errors['created_at'] = "created_at is required";
            }

            if (!Validator::in($this->statusList)->validate($data['status'])) {
                $this->errors['status'] = "status is required";
            }

        } elseif ($this->scenario == Model::EDIT_SCENARIO) {
            if (!Validator::stringType()->notEmpty()->validate($data['username'])) {
                $this->errors['username'] = "username is required";
            }

            if ($this->checkUserByName($data['username'], $this->id)) {
                $this->errors['username'] = "username is busy";
            }

            if(!Validator::email()->validate($data['email'])) {
                $this->errors['email'] = "email is required";
            }

            if ($this->checkUserByEmail($data['email'], $this->id)) {
                $this->errors['email'] = "email is busy";
            }

            if($data['password'] != '') {
                $errPass = false;
                if (!Validator::stringType()->length(self::MIN_PASSWORD_LENGTH, null)->validate($data['password'])) {
                    $this->errors['password'] = "Min password length is ".self::MIN_PASSWORD_LENGTH;
                    $errPass = true;
                }

                if ($data['password'] != $data['confirmPassword']) {
                    $this->errors['confirmPassword'] = "password is not confirm";
                    $errPass = true;
                }

                if($errPass == false)
                {
                    $data['password'] = $this->generateHash($data['password']);
                }
            } else {
                $data['password'] = $this->password;
            }

        }

        if(!$this->errors)
        {
            $this->loadData($data);
        }

        return $this->errors;
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

       return $this->saveModel();
    }

    public function saveModel()
    {
        $data = [
            'id' => $this->id,
            'username' => $this->username,
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
                                    `username` VARCHAR(255),
                                    `password` VARCHAR (255),
                                    `email` VARCHAR (255),
                                    `created_at` VARCHAR (12),
                                    `confirm_token` VARCHAR (255),
                                    `status` VARCHAR (10) )');

    }


    /**
     * @param $name
     * @param bool $id
     * @return array
     */
    public function checkUserByName($name, $id = false)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE username = :username LIMIT 1';

        if ($id) {
            $query = 'SELECT * FROM '.$this->table.' WHERE username = :username AND NOT id = :id LIMIT 1';
        }

        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $name);
        $statement->bindValue(':id', $id);

        $result = $statement->execute();


        return $result->fetchArray();
    }

    /**
     * @param $email
     * @return array
     */
    public function checkUserByEmail($email, $id = false)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE email = :email  LIMIT 1';

        if ($id) {
            $query = 'SELECT * FROM '.$this->table.' WHERE email = :email AND NOT id = :id LIMIT 1';
        }

        $statement = $this->db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':id', $id);

        $result = $statement->execute();

        return $result->fetchArray();
    }


    /**
     * @param $token
     * @return array
     */
    public function getUserByConfirmToken($token)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE confirm_token = :token LIMIT 1';

        $statement = $this->db->prepare($query);
        $statement->bindValue(':token', $token);

        $result = $statement->execute();

        return $result->fetchArray();
    }


    /**
     * @param $id
     * @return array
     */
    public function getUserById($id)
    {
        $query = 'SELECT * FROM '.$this->table.' WHERE id = :id LIMIT 1';

        $statement = $this->db->prepare($query);
        $statement->bindValue(':id', $id);

        $result = $statement->execute();

        return $result->fetchArray();
    }

    private function loadData($data)
    {
        if(isset($data['id'])) {
            $this->setId($data['id']);
        }

        $this->setUsername($data['username']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setStatus($data['status']);
        $this->setCreated($data['created_at']);
        $this->setConfirmToken($data['confirm_token']);
    }

}