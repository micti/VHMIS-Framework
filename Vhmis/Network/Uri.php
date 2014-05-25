<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Network;

class Uri
{
    /**
     * Protocol
     *
     * @var string
     */
    protected $scheme = 'http';

    /**
     * Domain
     *
     * @var string
     */
    protected $host = '';

    /**
     * Path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Query
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
     * Valid
     *
     * @var bool
     */
    protected $valid = false;

    /**
     * Construct
     *
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        // Nếu $uri là rỗng, thì xem như chỉ khởi tạo đối tượng
        if ($uri == '') {
            return;
        }

        $this->setUri($uri);
    }

    /**
     * Set uri from string
     *
     * @param string $uri
     *
     * @return \Vhmis\Network\Uri
     */
    public function setUri($uri)
    {
        $this->prase($uri);

        return $this;
    }

    /**
     * Prase URI
     *
     * $param string $uri
     */
    protected function prase($uri)
    {
        // Phải bắt đầu bằng http hoặc https
        $scheme = explode(':', $uri, 2);
        $scheme = strtolower($scheme[0]);
        if (in_array($scheme, array('http', 'https')) === false) {
            $this->valid = false;

            return;
        }

        // Phân tích url
        $result = parse_url($uri);

        if ($result === false) {
            $this->valid = false;

            return;
        }

        foreach ($result as $key => $value) {
            $this->{$key} = $value;
        }

        $this->valid = true;
    }

    /**
     * Get valid of URI
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Get protocol
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->scheme;
    }

    /**
     * Get host / domain
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->host != '' ? $this->path : false;
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query != '' ? $this->query : false;
    }

    /**
     * Get fragment
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment != '' ? $this->fragment : false;
    }

    /**
     * Get full uri
     *
     * @return string
     */
    public function getURI()
    {
        if (!$this->valid) {
            return '';
        }

        $uri = $this->scheme . '://'
            . (($this->user != '' && $this->pass != '') ? $this->user . ':' . $this->pass . '@' : '')
            . $this->host
            . $this->path
            . (($this->query != '') ? '?' . $this->query : '')
            . (($this->fragment != '') ? '#' . $this->fragment : '');

        return $uri;
    }
}
