<?php

namespace Vhmis\Cache;

abstract class AdapterFactory
{

    /**
     *
     * @param string $adapter            
     * @param type $config            
     * @return \Vhmis\Cache\Adapter\StorageInterface
     */
    public static function fatory($adapter, $config)
    {
        $class = 'Vhmis\\Cache\\Adapter\\' . $adapter;
        $adapter = new $class($config);
        
        return $adapter;
    }
}
