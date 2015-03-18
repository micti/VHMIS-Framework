<?php

namespace Vhmis\Http;

/**
 * Server request interface.
 *
 * PSR7 Http Message.
 */
interface ServerRequestInterface extends RequestInterface
{

    public function getServerParams();

    public function getCookieParams();

    public function withCookieParams(array $cookies);

    public function getQueryParams();

    public function withQueryParams(array $query);

    public function getFileParams();

    public function getParsedBody();

    public function withParsedBody($data);

    public function getAttributes();

    public function getAttribute($name, $default = null);

    public function withAttribute($name, $value);

    public function withoutAttribute($name);
}
