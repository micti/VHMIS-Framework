<?php

class Vhmis_Collection_Components extends Vhmis_Collection_Objects
{
    public function load($name, $param = null)
    {
        $class = 'Vhmis_Component_' . $name;
        $name = ___ctv($name);
        $this->_loaded[$name] = new $class($param);

        return $this->_loaded[$name];
    }
}