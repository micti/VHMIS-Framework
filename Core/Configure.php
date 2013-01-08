<?php

class Configure extends ArrayObject
{
    private static $_configure = null;

    public static function getInstance()
    {
        if (self::$_configure === null) {
             self::$_configure = new Configure();
        }

        return self::$_configure;
    }

    public static function get($index)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index)) {
            return null;
        }

        return $instance->offsetGet($index);
    }

    public static function set($index, $value)
    {
        $instance = self::getInstance();

        $instance->offsetSet($index, $value);
    }

    public static function isRegistered($index)
    {
        if (self::$_configure === null) {
            return false;
        }
        return self::$_configure->offsetExists($index);
    }

    public function offsetExists($index)
    {
        return array_key_exists($index, $this);
    }

    public static function add($index, $value)
    {
        $instance = self::getInstance();

        if (!$instance->offsetExists($index))
        {
            $instance->offsetSet($index, $value);
        }
        else
        {
            $instance->offsetSet($index, array_merge_recursive($instance->offsetGet($index), $value));
        }
    }

}