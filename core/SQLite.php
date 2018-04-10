<?php

namespace core;

use SQLite3;

class SQLite extends SQLite3 {

    protected static $instance = null;

    public static function instance() {
        if ( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __destruct(){}


    public function __construct()
    {
        $settings = parse_ini_file(ROOT_PATH . '/config/settings.ini', true);
        $databaseName = $settings['database']['dbname'];

        $this->open(ROOT_PATH . '/database/'.$databaseName);
    }


    public function __clone()
    {
        return false;
    }
    public function __wakeup()
    {
        return false;
    }
}
