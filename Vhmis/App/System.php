<?php

namespace Vhmis\App;

/* 
 * System have many applications
 */

class System
{
    protected $apps = [];
    
    public function __construct($path, $appIdentityType = null)
    {
        $this->systemPath = $path;
        $this->$appIdentityType = $appIdentityType !== null ? $appIdentityType : 'path';
    }
    
    public function addApp($identity, $name, $params)
    {
        $this->apps[$identity] = [
            'name' => $name,
            'params' => $params
        ];
    }
    
    public function getApp();
}

