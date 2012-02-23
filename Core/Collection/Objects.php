<?php

abstract class Vhmis_Collection_Objects
{
    protected $_loaded = array();

    abstract public function load($name, $param);

    public function __get($name) {
        if(isset($this->_loaded[$name])) {
            return $this->_loaded[$name];
        }

        return null;
    }

    public function set($name, $object)
    {
        $this->_loaded[$name] = $object;
        return $this->_loaded[$name];
    }

    public function __set($name, $object)
    {
        $this->_loaded[$name] = $object;
        return $this->_loaded[$name];
    }
}