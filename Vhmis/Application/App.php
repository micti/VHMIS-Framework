<?php

namespace Vhmis\Application;

use \Vhmis\Config;
use \Vhmis\Network;
use \Vhmis\Di;

class App
{
    /**
     * Router
     *
     * @var \Vhmis\Network\Router
     */
    protected $router;

    /**
     * Request
     *
     * @var \Vhmis\Network\Request
     */
    protected $request;

    /**
     * Điều khiển toàn bộ quá trình xử lý của hệ thống
     */
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {
        // Lấy config
        $configGlobal = Config\Config::system('Global');
        $configApp = Config\Config::system('Applications');
        Config\Configure::set('ConfigGlobal', $configGlobal);
        Config\Configure::set('ConfigApplications', $configApp);

        // Các đối tượng trợ giúp
        $this->router = new Network\Router();
        $this->request = new Network\Request();

        $this->router->setting($configGlobal['app']['use'], $configGlobal['language']['multi'], $configGlobal['language']['position'], $configGlobal['app']['default'], $configGlobal['locale']['lang']);
        $this->router->homeRoute($configApp['indexAppInfo'])->webPath($configGlobal['site']['path']);

        $this->request->setRouter($this->router);
        $this->request->process();

        // Khai báo autoload
        $auto = new Autoload(SYSTEM, VHMIS_SYS2_PATH);
        $auto->register();

        // Khai báo di, service manager;
        $di = new Di\Di();
        $sm = $di->get('Vhmis\Di\ServiceManager');
        $sm->setConnections();

        // Thuc thi
        if ($this->request->responeCode === '200') {

            //Load cac service vào Di
            $services = Config\Config::system('Service');
            foreach ($services as $name => $service) {
                $sm->set($name, $service, true);
            }

            $controllerClass = '\\' . SYSTEM . '\\Apps\\' . ucfirst($this->request->app['app']) . '\\Controller\\' . $this->request->app['controller'];
            $_vhmisController = new $controllerClass($this->request);
            $_vhmisController->setServiceManager($sm);
            $_vhmisController->init();
        } else {
            echo $this->request->responeCode;
        }
    }
}
