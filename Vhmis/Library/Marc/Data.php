<?php

namespace Vhmis\Library\Marc;

class Data
{

    static protected $data;

    public static function getField($code)
    {
        self::getData('Fields');

        if (isset(self::$data[$code])) {
            return self::$data[$code];
        }

        return null;
    }

    protected static function getData($name)
    {
        if (!isset(self::$data[$name])) {
            self::$data[$name] = include 'Data/' . $name . 'php';
        }
    }
}
