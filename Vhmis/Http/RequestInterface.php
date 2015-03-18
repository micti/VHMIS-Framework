<?php

namespace Vhmis\Http;

/**
 * Request interface.
 *
 * PSR7 Http Message.
 */
interface RequestInterface extends MessageInterface
{
    public function getHeaders();

    public function getHeader($name);

    public function getHeaderLines($name);

    public function getRequestTarget();

    public function withRequestTarget($requestTarget);

    public function getMethod();

    public function withMethod($method);

    public function getUri();

    public function withUri(UriInterface $uri);
}
