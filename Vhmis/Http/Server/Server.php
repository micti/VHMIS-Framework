<?php

namespace Vhmis\Http\Server;

use Vhmis\Http\ResponseInterface;
use Vhmis\Http\RequestInterface;
use Vhmis\Http\Response;
use Vhmis\Http\ServerRequest;

class Server
{

    /**
     *
     * @var callable
     */
    protected $app;

    /**
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     *
     * @param callable $app
     * @param ResponseInterface $request
     * @param RequestInterface $response
     */
    public function __construct($app, $request = null, $response = null)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    public function run()
    {
        if (!is_callable($this->app)) {
            return $this->sendError();
        }

        $app = $this->app;
        $result = $app($this->getRequest(), $this->getResponse());

        if (!($result instanceof ResponseInterface)) {
            $result = $this->getResponse();
        }

        $this->sendResult($result);
    }

    public function listen()
    {
        $this->run();
    }

    public function dispatch()
    {
        $this->run();
    }

    /**
     *
     * @param ResponseInterface $response
     */
    public function sendResult($response)
    {
        // Header;
        // Body;
        echo $response->getBody();
        // Or $response->response();
    }

    public function sendError()
    {
        $request = $this->getRequest();
        header('HTTP/' . $request->getProtocolVersion() . ' 501 Internal Error');
    }

    /**
     *
     * @return ResponseInterface
     */
    protected function getResponse()
    {
        if (!($this->response instanceof ResponseInterface)) {
            $this->response = new Response(200);
        }
        return $this->response;
    }

    /**
     *
     * @return RequestInterface
     */
    protected function getRequest()
    {
        if (!($this->request instanceof RequestInterface)) {
            $this->request = new ServerRequest();
        }

        return $this->request;
    }
}
