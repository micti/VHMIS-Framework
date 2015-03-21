<?php

namespace Vhmis\Http;

use Vhmis\Utils\Exception\InvalidArgumentException;

class Request implements RequestInterface
{

    use MessageTrait;

    /**
     * Methods
     * 
     * @var string[]
     */
    protected $methods = [
        'CONNECT',
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'PATCH',
        'POST',
        'PUT',
        'TRACE',
    ];

    /**
     * Request method.
     * 
     * @var string
     */
    protected $method;

    /**
     * Request target
     * 
     * @var string
     */
    protected $requestTarget;

    /**
     *
     * @var Uri
     */
    protected $uri;

    /**
     * 
     * @param string $method
     * @param string|UriInterface $url
     * @param array $headers
     * @param StreamableInterface $body
     * 
     * @throws InvalidArgumentException
     */
    public function __construct($method, $url, $headers = [], $body = null)
    {
        if (!in_array($method, $this->methods, true)) {
            throw new InvalidArgumentException('Invalid method');
        }

        $this->method = $method;

        if (!is_string($url) && !($url instanceof UriInterface)) {
            throw new InvalidArgumentException('Invalid uri');
        }

        $this->uri = $url;
        if (is_string($url)) {
            $this->uri = new Uri($url);
        }

        foreach ($headers as $key => $value) {
            $prepareValue = $this->prepareHeader($name, $value);
            $this->headers[$prepareValue[0]] = $prepareValue[1];
        }

        $this->body = $body;
    }

    public function getRequestTarget()
    {
        if ($this->requestTarget !== null) {
            return $this->requestTarget;
        }

        if (!$this->uri) {
            return '/';
        }

        $uri = $this->uri->getPath();
        if ($this->uri->getQuery()) {
            $uri .= '?' . $this->uri->getQuery();
        }

        return $uri;
    }

    /**
     * Create a new instance with a specific request-target.
     * 
     * @param mixed $requestTarget
     * 
     * @return self
     */
    public function withRequestTarget($requestTarget)
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->method);
    }

    /**
     * 
     * @param string $method
     * @return \Vhmis\Http\Request
     * @throws InvalidArgumentException
     */
    public function withMethod($method)
    {
        if (!in_array(strtoupper($method), $this->methods, true)) {
            throw new InvalidArgumentException('Invalid method');
        }

        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    /**
     * Get uri.
     * 
     * @return UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Create a new instance with the provided URI.
     * 
     * @param UriInterface $uri
     * 
     * @return self
     */
    public function withUri(UriInterface $uri)
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}
