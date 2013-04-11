<?php

namespace Vhmis\Application;

use \Vhmis\Config;
use \Vhmis\Network;

class App
{

    /**
     * Router
     *
     * @var \Vhmis\Network\Router
     */
    protected $_router;

    /**
     * Request
     *
     * @var \Vhmis\Network\Request
     */
    protected $_request;

    /**
     * Điều khiển toàn bộ quá trình xử lý của hệ thống
     */
    public function __construct()
    {
        // Thử nghiệm ver2
        $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" .
             $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, '_WWW/work/') !== false) {
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
        Config\Configure::set('ConfigDatabase', Config\Config::system('Database'));

        // Các đối tượng trợ giúp
        $this->_router = new Network\Router();
        $this->_request = new Network\Request();

        $this->_router->setting($configGlobal['app']['use'], $configGlobal['language']['multi'],
            $configGlobal['language']['position'], $configGlobal['app']['default'], $configGlobal['locale']['lang'])
            ->homeRoute($configApp['indexAppInfo'])
            ->webPath($configGlobal['site']['path']);
        $this->_request->setRouter($this->_router);
        $this->_request->process();

        // Khai báo autoload
        $auto = new Autoload('VhmisApps', VHMIS_SYS_PATH);
        $auto->register();

        if ($this->_request->responeCode === '200') {
            $controllerClass = 'VhmisApps\\' . ucfirst($this->_request->app['app']) . '\\Controller\\' .
                 $this->_request->app['controller'];
            $_vhmisController = new $controllerClass($this->_request);
            $_vhmisController->init();

            exit();
        }

        echo $configGlobal['site']['path'] . ' -- ' . $this->_request->responeCode;
        var_dump($this->_request->app);
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
