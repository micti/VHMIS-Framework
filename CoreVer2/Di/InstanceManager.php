<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class chứa các service được định nghĩa
 *
 * @author Micti
 */
class InstanceManager
{
    protected $_services;

    protected $_lazyServices;

    protected $_params;

    protected $_lazyParams;

    public function add($instance, $class)
    {
        if(!is_object($instance))
        {
            throw new \Exception("Not object");
        }

        if($service instanceof \Closure)
        {
            $this->_lazyServices[$class] = $instance;
        }
        else
        {
            $this->_services[$class] = $instance;
        }
    }

    public function addParams($params, $class)
    {
        if($params instanceof \Closure)
        {
            $this->_lazyParams[$class] = $params;
        }

        $this->_params[$class] = $params;
    }

    public function get($class)
    {
        if(isset($this->_services[$class])) {
            return $this->_services[$class];
        }

        if(isset($this->_closure[$class])) {
            $this->_services[$class] = $this->_closure[$id]();
            return $this->_services[$id];
        }

        return null;
    }
}
