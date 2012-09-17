<?php

class Vhmis_Network_Http
{
    protected $_adapters = array('CURL');

    public function __construct($adapter)
    {
        if(!in_array($adapter, $this->_adapters)) return false;

        $adapter = 'Vhmis_Network_Http_' . ___fUpper($adapter);

        return new $adapter();
    }
}