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
     * Response
     *
     * @var \Vhmis\Network\Response
     */
    protected $response;

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
        $this->response = new Network\Response();

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

            // Set default timezone
            date_default_timezone_set($configGlobal['timezone']['name']);

            // Ngôn ngữ
            Config\Configure::set('Locale', $configGlobal['locale']['lang'] . '_' . $configGlobal['locale']['region']);
            locale_set_default($configGlobal['locale']['lang'] . '_' . $configGlobal['locale']['region']);

            $controllerClass = '\\' . SYSTEM . '\\Apps\\' . ucfirst($this->request->app['app']) . '\\Controller\\' . $this->request->app['controller'];
            $controller = new $controllerClass($this->request, $this->response);
            $controller->setServiceManager($sm);
            $controller->init();
        } else {
            $this->response->reponseError($this->request->responeCode);
        }
    }
}
