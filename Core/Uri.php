<?php

/**
 * URI
 *
 * Thiết lập, phân tích URI, trả về các thông số cần thiết cho boot.php xử lý request
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright     Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link          https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category      VHMIS
 * @package       Core
 * @subpackage    URI
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 * Lấy thông tin URI
 */
class Vhmis_Uri
{
    protected $_protocol = 'http';
    protected $_domain = '';
    protected $_path = '';
    protected $_query = '';
    protected $_username = '';
    protected $_password = '';
    protected $_fragment = '';
    protected $_valid = false;

    /**
     * Khởi tạo
     *
     * @param string $uri địa chỉ, phải bắt đầu bằng https hoặc http
     **/
    public function __construct($uri = '')
    {
        // Nếu $uri là rỗng, thì xem như chỉ khởi tạo đối tượng
        if($uri == '') return;

        $this->_prase($uri);
    }

    /**
     * Khởi một đối tượng mới từ 1 địa chỉ
     *
     * @param string $uri địa chỉ, phải bắt đầu bằng https hoặc http
     * @return VHMIS_URI đối tượng được khởi tạo
     **/
    public function fromString($uri)
    {
        return Vhmis_Uri($uri);
    }

    /**
     * Phân tích địa chỉ thành các thành phần
     *
     * @param string $uri địa chỉ
     **/
    protected function _prase($uri)
    {
        // Phải bắt đầu bằng http hoặc https
        $scheme = explode(':', $uri, 2);
        $scheme = strtolower($scheme[0]);
        if(in_array($scheme, array('http', 'https')) === false)
        {
            $this->_valid = false;
            return;
        }

        // Phân tích url
        $result = @parse_url($uri);

        if($result === false)
        {
            $this->_valid = false;
            return;
        }

        $this->_protocol = isset($result['scheme']) ? $result['scheme'] : 'http';
        $this->_domain = isset($result['host']) ? $result['host'] : '';
        $this->_path = isset($result['path']) ? $result['path'] : '';
        $this->_query = isset($result['query']) ? $result['query'] : '';
        $this->_fragment = isset($result['fragment']) ? $result['fragment'] : '';
        $this->_username = isset($result['user']) ? $result['user'] : '';
        $this->_password = isset($result['pass']) ? $result['pass'] : '';
        $this->_valid = true;
    }

    /**
     * Kiểm tra tính hợp lệ địa chỉ hiện thời của đối tượng
     *
     * @return boolean TRUE nếu địa chỉ hiện thời đúng, FALSE nếu địa chỉ hiện thời sai
     **/
    public function valid()
    {
        return $this->_valid;
    }

    /**
     * Lấy protocol của địa chỉ hiện thời
     *
     * @return string
     **/
    public function getProtocol()
    {
        return $this->_protocol;
    }

    /**
     * Lấy domain (ip) của địa chỉ hiện thời
     *
     * @return string
     **/
    public function getDomain()
    {
        return $this->_domain;
    }

    /**
     * Lấy đường dẫn của địa chỉ hiện thời
     *
     * @return string
     **/
    public function getPath()
    {
        return $this->_domain != '' ? $this->_path : false;
    }

    /**
     * Lấy query của địa chỉ hiện thời
     *
     * @return string
     **/
    public function getQuery()
    {
        return $this->_query != '' ? $this->_query : false;
    }

    /**
     * Lấy fragment (neo) của địa chỉ hiện thời
     *
     * @return string
     **/
    public function getFragment()
    {
        return $this->_fragment != '' ? $this->_fragment : false;
    }

    /**
     * Lấy địa chỉ hiện thời
     *
     * @return string
     **/
    public function getURI()
    {
        if(!$this->_valid) return '';

        $uri = $this->_protocol . '://'
             . (($this->_username != '' && $this->_password != '') ? $this->_username . ':' . $this->_password . '@' : '')
             . $this->_domain
             . $this->_path
             . (($this->_query != '') ? '?' . $this->_query : '')
             . (($this->_fragment != '') ? '#' . $this->_fragment : '');

        return $uri;
    }
}