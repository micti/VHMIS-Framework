<?php

class Vhmis_Collection_Shares extends Vhmis_Collection_Objects
{
    public function load($name, $param = null)
    {
        $class = 'Vhmis_Share_' . $name;
        $name = ___ctv($name);

        if(isset($this->_loaded[$name])) return $this->_loaded[$name];

        if($param == null)
        {
            $this->_loaded[$name] = new $class();
        }
        else
        {
            $this->_loaded[$name] = new $class($param);
        }

        return $this->_loaded[$name];
    }
}