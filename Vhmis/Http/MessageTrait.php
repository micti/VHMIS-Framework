<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Http;

use Vhmis\Utils\Exception\InvalidArgumentException;

trait MessageTrait
{

    /**
     * Body.
     * 
     * @var StreamableInterface
     */
    protected $body;

    /**
     * HTTP protocol version.
     * 
     * @var string
     */
    protected $protocol = '1.1';

    /**
     * Http headers.
     * 
     * @var array
     */
    protected $headers = [];

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string.
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * Create a new instance with the specified HTTP protocol version.
     *
     * @param string $version
     * 
     * @return self
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->protocol = $version;

        return $new;
    }

    /**
     * Retrieves all message headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name)
    {
        $headers = $this->getHeaderLines($name);

        return implode(',', $headers);
    }

    /**
     * 
     * @param string $name
     * 
     * @return string[]
     */
    public function getHeaderLines($name)
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        $headers = $this->headers[$header];

        if (!is_array($headers)) {
            return [$headers];
        }

        return $headers;
    }

    /**
     * Create a new instance with the provided header, replacing any existing
     * values of any headers with the same case-insensitive name.
     * 
     * @param string $name
     * @param string|string[] $value
     * 
     * @return self
     * 
     * @throws InvalidArgumentException.
     */
    public function withHeader($name, $value)
    {
        $prepareValue = $this->prepareHeader($name, $value);

        $new = clone $this;
        $new->headers[$prepareValue[0]] = $prepareValue[1];

        return $new;
    }

    public function withAddedHeader($name, $value)
    {
        $prepareValue = $this->prepareHeader($name, $value);

        $currentValue = $this->getHeaderLines($prepareValue[0]);
        $newValue = array_merge($currentValue, $prepareValue[1]);

        $new = clone $this;
        $new->headers[$prepareValue[0]] = $newValue;

        return $new;
    }

    /**
     * 
     * @param string $name
     * 
     * @return self
     */
    public function withoutHeader($name)
    {
        if (!$this->hasHeader($name)) {
            return $this;
        }

        $new = clone $this;
        unset($new->headers[$name]);

        return $new;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamableInterface.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Create a new instance, with the specified message body.
     *
     * The body MUST be a StreamableInterface object.
     *
     * @param StreamableInterface $body
     *
     * @return self
     *
     * @throws \InvalidArgumentException.
     */
    public function withBody(StreamableInterface $body)
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * 
     * @param string $name
     * @param string[] $value
     * 
     * @return array
     * 
     * @throws InvalidArgumentException
     */
    protected function prepareHeader($name, $value)
    {
        $header = strtolower(trim($name));

        if (is_string($value)) {
            $value = [$value];
        }

        if (!is_array($value)) {
            throw new InvalidArgumentException('Invalid header value.');
        }

        foreach ($value as &$string) {
            if (!is_string($string)) {
                throw new InvalidArgumentException('Invalid header value. Must be string');
            }

            $string = trim($string);
        }

        return [$header, $value];
    }
}
