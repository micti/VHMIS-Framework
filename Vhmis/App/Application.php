<?php

namespace Vhmis\App;

use Vhmis\Http;

class Application implements ApplicationInterface
{

    protected $handlers = [
        'get' => [],
        'post' => [],
        'put' => [],
        'delete' => []
    ];
    protected $appPath;
    protected $config;

    /**
     *
     * @var \Vhmis\Di\ServiceManager
     */
    public $container;

    public function __construct($appPath)
    {
        if (!is_dir($appPath)) {
            throw new \InvalidArgumentException('Invalid app path.');
        }

        $this->appPath = $appPath;

        $this->container = new \Vhmis\Di\ServiceManager();
    }

    /**
     *
     * @param Http\RequestInterface $request
     * @param Http\ResponseInterface $response
     *
     * @return Http\ResponseInterface|mixed
     */
    public function __invoke(Http\RequestInterface $request, Http\ResponseInterface $response)
    {
        $method = $request->getMethod();

        $uripath = $request->getUri()->getPath();
        $sitepath = $this->getAppConfig('site.path');
        $len = strlen($sitepath);
        $realpath = substr($uripath, $len);
        if ($realpath === '') {
            $realpath = '/';
        }

        foreach ($this->handlers[$method] as $path => $handler) {
            // If matching path;
            if ($realpath === $path) {
                return $handler($this, $request, $response);
            }
        }

        // 404
        $response404 = $response->withStatus(404);
        return $response404;
    }

    public function get($path, $handler)
    {
        if (!is_callable($handler)) {
            throw new \InvalidArgumentException('Handler must be callable');
        }

        $this->handlers['get'][$path] = $handler;
    }

    /**
     *
     * @return Application
     */
    public function loadConfig()
    {
        $path = $this->appPath . '/Config/App.php';
        $this->config['app'] = include $path;

        return $this;
    }

    /**
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAppConfig($key)
    {
        return isset($this->config['app'][$key]) ? $this->config['app'][$key] : null;
    }
}
