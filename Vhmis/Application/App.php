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
        // Thử nghiệm ver2
        $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" .
            $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, '_WWW/work') !== false) {
            $this->run();
        } else {
            $this->ver1Legacy();
        }
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
        }
    }

    public function ver1Legacy()
    {
        /**
         * Cấu hình
         */
        $_config = ___loadConfig('Applications', false);
        Config\Configure::set('Config', $_config);
        $_config = ___loadConfig('Global', false);
        Config\Configure::add('Config', $_config);

        // Set timezone +7
        \Vhmis_Date::setTimeZone($_config['timezone']['name']);

        // Ngôn ngữ
        Config\Configure::set('Locale', $_config['locale']['lang'] . '_' . $_config['locale']['region']);

        /**
         * Lấy uri, xử lý
         */
        $_vhmisRequest = new \Vhmis_Network_Request();
        $_vhmisResponse = new \Vhmis_Network_Response();

        if ($_vhmisRequest->responeCode == '403' || $_vhmisRequest->responeCode == '404') {
            $_vhmisView = new \Vhmis_View();
            $_vhmisView->transferConfigData(Config\Configure::get('Config'));
            ob_start();
            $_vhmisView->renderError('4xx');
            $content = ob_get_clean();

            // need rewrite;
            header('HTTP/1.1 404 Not Found');

            $_vhmisResponse->body($content);
            $_vhmisResponse->response();
            exit();
        }

        /**
         * Chuyển hướng
         */
        if (is_string($_vhmisRequest->app['info']['redirect']) && $_vhmisRequest->app['info']['redirect'] !== '') {
            // To do : cần viết lại đoạn này

            header('Location: ' . $_config['site']['path'] . $_vhmisRequest->app['info']['redirect']);
            exit();
        }

        /**
         * Gọi config của App
         */
        $_config = ___loadAppConfig($_vhmisRequest->app['url'], false);
        Config\Configure::add('Config', $_config);

        /**
         * Gọi Controller
         */
        $_vhmisController = ___loadController($_vhmisRequest, $_vhmisResponse);

        /**
         * Thực thi chương trình
         */
        $_vhmisController->init();
    }
}
