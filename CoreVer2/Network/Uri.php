<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Network
 * @since Vhmis v2.0
 */
namespace Vhmis\Network;

/**
 * Class dùng để xử lý địa chỉ yêu cầu
 *
 * @category Vhmis
 * @package Vhmis_Network
 * @subpackage Uri
 */
class Uri
{

    /**
     * Giao thức
     *
     * @var string
     */
    protected $_protocol = 'http';

    /**
     * Tên miền
     *
     * @var string
     */
    protected $_domain = '';

    /**
     * Đường dẫn
     *
     * @var string
     */
    protected $_path = '';

    /**
     * Tham số query
     *
     * @var string
     */
    protected $_query = '';

    /**
     * Username
     *
     * @var string
     */
    protected $_username = '';

    /**
     * Password
     *
     * @var string
     */
    protected $_password = '';

    /**
     * Neo (sau hashtag #)
     *
     * @var string
     */
    protected $_fragment = '';

    /**
     * Tính hợp lệ của địa chỉ
     *
     * @var bool
     */
    protected $_valid = false;

    /**
     * Khởi tạo
     *
     * @param string $uri địa chỉ, phải bắt đầu bằng https hoặc http
     */
    public function __construct($uri = '')
    {
        // Nếu $uri là rỗng, thì xem như chỉ khởi tạo đối tượng
        if ($uri == '')
            return;
        
        $this->addUri($uri);
    }

    /**
     * Khởi một đối tượng mới từ 1 địa chỉ
     *
     * @param string $uri địa chỉ, phải bắt đầu bằng https hoặc http
     * @return VHMIS_URI đối tượng được khởi tạo
     */
    public function addUri($uri)
    {
        $this->_prase($uri);
    }

    /**
     * Phân tích địa chỉ thành các thành phần
     */
    protected function _prase($uri)
    {
        // Phải bắt đầu bằng http hoặc https
        $scheme = explode(':', $uri, 2);
        $scheme = strtolower($scheme[0]);
        if (in_array($scheme, array(
            'http',
            'https'
        )) === false) {
            $this->_valid = false;
            return;
        }
        
        // Phân tích url
        $result = @parse_url($uri);
        
        if ($result === false) {
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
     * @return boolean
     */
    public function valid()
    {
        return $this->_valid;
    }

    /**
     * Lấy protocol của địa chỉ hiện thời
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->_protocol;
    }

    /**
     * Lấy domain (ip) của địa chỉ hiện thời
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->_domain;
    }

    /**
     * Lấy đường dẫn của địa chỉ hiện thời
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_domain != '' ? $this->_path : false;
    }

    /**
     * Lấy query của địa chỉ hiện thời
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->_query != '' ? $this->_query : false;
    }

    /**
     * Lấy fragment (neo) của địa chỉ hiện thời
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->_fragment != '' ? $this->_fragment : false;
    }

    /**
     * Lấy địa chỉ hiện thời
     *
     * @return string
     */
    public function getURI()
    {
        if (!$this->_valid)
            return '';
        
        $uri = $this->_protocol . '://' . (($this->_username != '' && $this->_password != '') ? $this->_username . ':' .
             $this->_password . '@' : '') . $this->_domain . $this->_path .
             (($this->_query != '') ? '?' . $this->_query : '') .
             (($this->_fragment != '') ? '#' . $this->_fragment : '');
        
        return $uri;
    }
}
