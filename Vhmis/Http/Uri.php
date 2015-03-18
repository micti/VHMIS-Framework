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

/**
 * URI for HTTP requests.
 *
 * PSR7 Http Message.
 */
class Uri implements UriInterface
{

    /**
     * Protocol.
     *
     * @var string
     */
    protected $scheme = '';

    /**
     * Port.
     *
     * @var int
     */
    protected $port = 0;

    /**
     * Host.
     *
     * @var string
     */
    protected $host = '';

    /**
     * Path.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Querystring.
     *
     * @var string
     */
    protected $query = '';

    /**
     * Username
     *
     * @var string
     */
    protected $user = '';

    /**
     * Password
     *
     * @var string
     */
    protected $pass = '';

    /**
     * Fragment
     *
     * @var string
     */
    protected $fragment = '';

    /**
     * Construct
     *
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $this->setUri($uri);
    }

    /**
     * Set uri from string
     *
     * @param string $uri
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function setUri($uri)
    {
        if (!is_string($uri)) {
            throw new InvalidArgumentException('Invalid uri.');
        }

        $this->prase($uri);

        return $this;
    }

    /**
     * Get scheme.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get user info.
     *
     * user[:pass] or empty
     *
     * @return string
     */
    public function getUserInfo()
    {
        $info = $this->user;

        if (!empty($this->pass)) {
            $info .= ':' . $this->pass;
        }

        return $info;
    }

    /**
     * Get authority.
     *
     * user[:pass]://host[:port]
     *
     * @return string
     */
    public function getAuthority()
    {
        if (empty($this->host)) {
            return '';
        }

        $authority = $this->host;
        $userinfo = $this->getUserInfo();
        if (!empty($userinfo)) {
            $authority = $userinfo . '@' . $authority;
        }

        if ($this->isNonStandardPort()) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * Get port.
     *
     * @return null|int
     */
    public function getPort()
    {
        if ($this->port === 0) {
            return null;
        }

        return $this->port;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        if (strpos($this->path, '/') !== 0) {
            return '/' . $this->path;
        }

        return $this->path;
    }

    /**
     * Get query.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get fragment.
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Get the string representation of the URI.
     *
     * @return string
     */
    public function getURI()
    {
        $uri = '';

        if (!empty($this->scheme)) {
            $uri .= $this->scheme . '://';
        }

        $authority = $this->getAuthority();
        if (!empty($authority)) {
            $uri .= $authority;
        }

        $uri .= $this->getPath();

        if (!empty($this->query)) {
            $uri .= '?' . $this->query;
        }

        if (!empty($this->fragment)) {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    /**
     * Return the string representation of the URI.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getURI();
    }

    /**
     * Create a new instance with the specified scheme.
     *
     * @param string $scheme
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function withScheme($scheme)
    {
        if (!in_array($scheme, ['', 'http', 'https', 'http://', 'https://'], true)) {
            throw new InvalidArgumentException('Invalid scheme specified');
        }

        $new = clone $this;
        $new->scheme = str_replace('://', '', $scheme);
        return $new;
    }

    /**
     *
     * @param string $user
     * @param null|string $password
     *
     * @return Uri
     */
    public function withUserInfo($user, $password = null)
    {
        $new = clone $this;

        $new->user = '';
        $new->pass = '';

        if ($user) {
            $new->user = $user;
            if ($password) {
                $new->pass = $password;
            }
        }

        return $new;
    }

    /**
     *
     * @param string $host
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function withHost($host)
    {
        if (!is_string($host)) {
            throw new InvalidArgumentException('Invalid host specified.');
        }

        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    /**
     *
     * @param int|string $port
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function withPort($port)
    {
        if ($port === null) {
            $new = clone $this;
            $new->port = 0;
            return $new;
        }

        if (!is_numeric($port)) {
            throw new InvalidArgumentException('Invalid port.');
        }

        $port = (int) $port;

        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException('Invalid port.');
        }

        $new = clone $this;
        $new->port = $port;

        return $new;
    }

    /**
     *
     * @param string $path
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function withPath($path)
    {
        if (!is_string($path)) {
            throw new InvalidArgumentException('Invalid path specified.');
        }

        $path = explode('?', $path);

        $new = clone $this;
        $new->path = $path[0];

        return $new;
    }

    /**
     *
     * @param string $query
     *
     * @return Uri
     *
     * @throws InvalidArgumentException
     */
    public function withQuery($query)
    {
        if (!is_string($query)) {
            throw new InvalidArgumentException('Invalid query string.');
        }

        $query = explode('#', $query);

        if (strpos($query[0], '?') === 0) {
            $query[0] = substr($query[0], 1);
        }

        $new = clone $this;
        $new->query = $query[0];

        return $new;
    }

    /**
     * Create a new instance with the specified URI fragment.
     *
     * @param string $fragment The URI fragment to use with the new instance.
     *
     * @return self A new instance with the specified URI fragment.
     */
    public function withFragment($fragment)
    {
        if (strpos($fragment, '#') === 0) {
            $fragment = substr($fragment, 1);
        }

        $new = clone $this;
        $new->fragment = $fragment;

        return $new;
    }

    /**
     * Prase URI
     *
     * @param string $uri
     */
    protected function prase($uri)
    {
        $result = parse_url($uri);

        if ($result !== false) {
            foreach ($result as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Is non standard port for current Uri.
     *
     * @return boolean
     */
    protected function isNonStandardPort()
    {
        if (!$this->scheme) {
            return true;
        }

        if (!$this->host || $this->port === 0) {
            return false;
        }

        if ($this->scheme === 'https' && $this->port !== 443) {
            return true;
        }

        if ($this->scheme === 'http' && $this->port !== 80) {
            return true;
        }

        return false;
    }

}
