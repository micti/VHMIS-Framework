<?php

namespace Vhmis\App;

use Vhmis\Http;

/**
 * Collection of some relative actions
 */
abstract class AbstractController
{

    /**
     * Construct.
     * 
     * @param Application $app
     * @param Http\RequestInterface $request
     * @param Http\ResponseInterface $response
     */
    public function __construct($app, $request, $response)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Run an action.
     * 
     * @param string $action
     * 
     * @return Http\ResponseInterface
     * 
     * @throws \InvalidArgumentException
     */
    public function __invoke($action)
    {
        if (!method_exists($this, $action)) {
            throw new \InvalidArgumentException('Invalid action');
        }

        $this->$action();

        return $this->response;
    }
}
