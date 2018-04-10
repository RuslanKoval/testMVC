<?php

namespace core;

class Register
{
    private static $field = array();
    /**
     * @param $key
     * @param $value
     */
    public static function setField($key, $value)
    {
        self::$field[$key] = $value;
    }
    /**
     * @param $key
     * @return mixed|null
     */
    public static function getField($key)
    {
        return isset(self::$field[$key]) ? self::$field[$key] : null;
    }

}