<?php

namespace core;

class Model
{
    protected $db = null;
    protected $table = "";

    public function __construct()
    {
        $this->db = SQLite::instance();
        $this->init();
    }

    public function init()
    {

    }

    /**
     * @param $table
     */
    protected function setTable($table)
    {
        $this->table = $table;
    }


    protected function save($data = array())
    {
        $sql = '';

        $values = array();

        if (isset($data['id']) && $data['id'] != '') {
            $sql = "UPDATE {$this->table} SET ";

            $first = true;
            foreach($data as $key => $value) {
                if ($key != 'id') {
                    $sql .= ($first == false ? ',' : '') . " $key = '{$value}'";
                    $values[] = $value;

                    $first = false;
                }
            }
            $values[] = $data['id'];
            $sql .= " WHERE id = {$data['id']}";

            $this->db->query($sql);

            return true;

        }
        else {
            unset($data['id']);
            $keys = array_keys($data);

            $sql = 'INSERT INTO ' . $this->table . ' (';
            $sql .= implode(',', $keys);
            $sql .= ')';
            $sql .= ' values (';

            $dataValues = $data;
            $first = true;

            foreach($dataValues as $key => $value) {
                $sql .= ($first == false ? ',"'.$value.'"' : '"'.$value.'"');

                $values[':'.$key] = $value;

                $first = false;
            }

            $sql .= ')';

            $this->db->query($sql);
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