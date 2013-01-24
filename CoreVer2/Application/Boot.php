<?php

namespace Vhmis\Application;

use Vhmis\Network;
use Vhmis\Config;

class Boot
{
    /**
     * Router
     *
     * @var Vhmis\Network\Router
     */
    protected $_router;

    /**
     * Request
     *
     * @var Vhmis\Network\Request
     */
    protected $_request;

    /**
     * Điều khiển toàn bộ quá trình xử lý của hệ thống
     */
    public function __construct()
    {
        // Lấy config
        $configGlobal = Config\Config::system('Global');
        $configApp = Config\Config::system('Applications');

        Config\Configure::set('ConfigGlobal', $configGlobal);
        Config\Configure::set('ConfigApplications', $configApp);
        Config\Configure::set('ConfigDatabase', Config\Config::system('Database'));

        // Các đối tượng trợ giúp
        $this->_router = new Network\Router();
        $this->_request = new Network\Request();

        $this->_router->setting($configGlobal['app']['use'], $configGlobal['language']['multi'], $configGlobal['language']['position'], $configGlobal['app']['default'], $configGlobal['locale']['lang'])
            ->homeRoute($configApp['indexAppInfo'])
            ->webPath($configGlobal['site']['path']);

        $this->_request->addRouter($this->_router);

        $this->_request->process();

        echo $configGlobal['site']['path'] . ' -- ' .$this->_request->responeCode;
        var_dump($this->_request->app);
    }
}