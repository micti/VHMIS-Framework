<?php

namespace Vhmis\Http;

class ServerRequest extends Request implements ServerRequestInterface
{

    /**
     *
     * @var array
     */
    protected $serverParams;

    /**
     *
     * @var array
     */
    protected $cookieParams;

    /**
     *
     * @var array
     */
    protected $queryParams;

    /**
     *
     * @var array
     */
    protected $fileParams;

    /**
     *
     * @var array
     */
    protected $parsedBody;

    /**
     *
     * @var array
     */
    protected $attributes;

    public function __construct($body = null)
    {
        $this->serverParams = $_SERVER;
        $this->cookieParams = $_COOKIE;
        $this->fileParams = $_FILES;
        $this->queryParams = $_GET;
        $this->parsedBody = $_POST;

        parent::__construct($this->serverParams['REQUEST_METHOD'], $this->getRequestUri(), [], $body);
    }

    public function getServerParams()
    {
        return $this->serverParams;
    }

    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * Create a new instance with the specified cookies.
     *
     * @param array $cookies
     * @return self
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookieParams = $cookies;

        return $new;
    }

    /**
     * Retrieve query string arguments.
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Create a new instance with the specified query string arguments.
     *
     * @param array $query
     * @return self
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    /**
     * Retrieve the upload file metadata.
     *
     * @return array
     */
    public function getFileParams()
    {
        return $this->fileParams;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return null|array|object.
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * Create a new instance with the specified body parameters.
     *
     * @param null|array|object
     *
     * @return self
     */
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = $data;

        return $new;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $default;
        }

        return $this->attributes[$name];
    }

    public function withAttribute($name, $value)
    {
        $new = clone $this;
        $new->attributes[$name] = $value;

        return $new;
    }

    public function withoutAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            return $this;
        }

        $new = clone $this;
        unset($new->attributes[$name]);

        return $new;
    }

    /**
     * Get request uri.
     *
     * @return string
     */
    protected function getRequestUri()
    {
        $server = $this->serverParams['SERVER_NAME'];
        $path = $this->serverParams['REQUEST_URI'];
        $protocol = !empty($this->serverParams['HTTPS']) ? 'https' : 'http';

        return $protocol . '://' . $server . $path;
    }
}
