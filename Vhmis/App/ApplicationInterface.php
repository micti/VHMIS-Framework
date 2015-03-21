<?php

namespace Vhmis\App;

use Vhmis\Http;

interface ApplicationInterface
{

    /**
     * Run app.
     *
     * @param Http\RequestInterface $request
     * @param Http\ResponseInterface $response
     */
    public function __invoke(Http\RequestInterface $request, Http\ResponseInterface $response);
}
