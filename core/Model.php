<?php

namespace core;

class Model
{
    const CREATE_SCENARIO = 1;
    const EDIT_SCENARIO = 2;
    const LOAD_SCENARIO = 3;

    protected $db = null;
    protected $table = "";
    protected $scenario = null;

    public function __construct()
    {
        $this->db = SQLite::instance();
        $this->setScenario(self::CREATE_SCENARIO);
        $this->init();
    }

    public function init()
    {

    }

    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @param $table
     */
    protected function setTable($table)
    {
        $this->table = $table;
    }


    public function save($data = array())
    {
        if (isset($data['id']) && $data['id'] != '') {
            $sql = "UPDATE {$this->table} SET ";

            $first = true;
            foreach($data as $key => $value) {
                if ($key != 'id') {
                    $sql .= ($first == false ? ',' : '') . " $key = :{$key}";
                    $first = false;
                }
            }

            $sql .= " WHERE id = :id";

            $statement = $this->db->prepare($sql);

            foreach($data as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }

            $statement->execute();

            return true;
        }
        else {
            $keys = array_keys($data);

            $sql = 'insert into ' . $this->table . '(';
            $sql .= implode(',', $keys);
            $sql .= ')';
            $sql .= ' values (';

            $first = true;
            foreach($data as $key => $value) {
                $sql .= ($first == false ? ',:'.$key : ':'.$key);

                $first = false;
            }

            $sql .= ')';
            $statement = $this->db->prepare($sql);
            foreach($data as $key => $value) {
                $statement->bindValue(':'.$key, $value);

            }

            $statement->execute();
            return $this->db->lastInsertRowID();
        }

        return false;
    }


    protected function randomString($count = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randString = '';
        for ($i = 0; $i < $count; $i++) {
            $randString.= $characters[rand(0, strlen($characters))];
        }
        return $randString;
    }
}