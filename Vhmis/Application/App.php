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

        // Các đối tượng trợ giúp
        $this->router = new Network\Router();
        $this->request = new Network\Request();
        $this->response = new Network\Response();

        $this->router->setting($configGlobal['app']['multi'], $configGlobal['language']['multi'], $configGlobal['language']['position'], $configGlobal['app']['default'], $configGlobal['language']['default'], $configGlobal['app']['list'], $configGlobal['language']['accept']);
        $this->router->homeRoute($configGlobal['app']['indexAppInfo'])->webPath($configGlobal['site']['path']);

        $this->request->setRouter($this->router);
        $this->request->process();

        // Thuc thi
        if ($this->request->responeCode === '200') {
            // Khai báo autoload
            $auto = new Autoload(SYSTEM, VHMIS_SYS_PATH);
            $auto->register();

            // Khai báo di, service manager;
            $di = new Di\Di();
            $sm = $di->get('Vhmis\Di\ServiceManager');

            // Database connections if exist
            if (isset($configGlobal['database']) && $configGlobal['database'] === true) {
                $sm->setConnections();
            }

            // Services  if exist
            if (isset($configGlobal['service']) && $configGlobal['service'] === true) {
                $services = Config\Config::system('Service');
                foreach ($services as $name => $service) {
                    $sm->set($name, $service, true);
                }
            }

            // Set default timezone
            date_default_timezone_set($configGlobal['timezone']['name']);

            // Locale
            $locale = $this->request->app['language'] . '_' .
                $configGlobal['language']['accept'][$this->request->app['language']];
            Config\Configure::set('Locale', $locale);
            \Locale::setDefault($locale);

            $controllerClass = '\\' . SYSTEM . '\\Apps\\' . $this->request->app['app'] .
                '\\Controller\\' . $this->request->app['controller'];
            $controller = new $controllerClass($this->request, $this->response);
            $controller->setServiceManager($sm);
            $controller->init();
        } else {
            $this->response->reponseError($this->request->responeCode);
        }
    }
}
