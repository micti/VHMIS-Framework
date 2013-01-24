<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link       http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright  Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @package    Vhmis_Network
 * @since      Vhmis v2.0
 */

namespace Vhmis\Network;

use Vhmis\Config\Configure;

/**
 * Xứ lý yêu câu (request) gửi đến
 *
 * @category   Vhmis
 * @package    Vhmis_Network
 */
class Request
{
    /**
     * Uri
     *
     * @var Vhmis\Network\Uri
     */
    protected $_uri;

    /**
     * Mảng chứa dữ liệu của POST.
     *
     * @var array
     */
    public $post = array();

    /**
     * Mảng chứa dữ liệu của GET (querystring).
     *
     * @var array
     */
    public $get = array();

    /**
     * Thông tin ứng dụng của request
     *
     * @var mixed
     */
    public $app;

    /**
     * Request code;
     *
     * @var string
     */
    public $responeCode;

    /**
     * Router
     *
     * @var \Vhmis\Network\Router
     */
    protected $_router;

    /**
     * Khởi tạo!
     *
     * @param string $url Địa chỉ url.
     */
    public function __construct(Router $router = null)
    {
        if($router != null) $this->_router = $router;
    }

    /**
     * Gán Router vào
     *
     * @param \Vhmis\Request\Router $router
     */
    public function addRouter(Router $router)
    {
        $this->_router = $router;
    }

    /**
     * Xử lý request
     *
     * @param string $url
     */
    public function process($url = null)
    {
        $this->responeCode = '200';

        $config = Configure::get('Config');

        if (empty($url)) {
            $url = $this->url();
        }

        $this->_uri = new Uri($url);

        if ($this->_uri->valid() == false) {
            $this->responeCode = '403';
            return;
        }
        
        $result = $this->_router->check($this->_uri->getPath());

        if ($result['match'] == false) {
            $this->responeCode = '404';
            return;
        }

        $this->app = $result;

        $this->_getPostData();
        $this->_getGetData();
        $this->_getFileData();
        $this->_delData();
    }

    /**
     * Lấy địa chỉ ip
     *
     * @return string
     */
    public function realIp()
    {
        $ip = '0.0.0.0';

        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (($commaPos = strpos($ip, ',')) > 0) {
            $ip = substr($ip, 0, ($commaPos - 1));
        }

        return $ip;
    }

    /**
     * Lấy địa chỉ url hiện tại.
     *
     * @return string
     */
    public function url()
    {
        return (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Lấy địa chỉ referrer
     *
     * @return string
     */
    public function referrer()
    {
        $referrer = $_SERVER['HTTP_REFERER'];
        $forward = $_SERVER['HTTP_X_FORWARDED_HOST'];

        if ($forward)
            return $forward;
        return $referrer;
    }

    /**
     * Lấy dữ liệu của phương thức POST.
     * Dữ liệu sau khi được lấy được lưu và truy xuất thuộc tính $data
     */
    protected function _getPostData()
    {
        $this->post = $_POST;

        // Loại bỏ ký tự \
        if (ini_get('magic_quotes_gpc') === '1') {
            $this->post = ___stripSlashes($this->post);
        }
    }

    /**
     * Lấy dữ liệu của phương thức GET (querystring).
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở thuộc tính $query.
     */
    protected function _getGetData()
    {
        $this->get = $_GET;

        // Loại bỏ ký tự \
        if (ini_get('magic_quotes_gpc') === '1') {
            $this->get = ___stripSlashes($this->get);
        }
    }

    /**
     * Lấy dữ liệu của $_FILE.
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở $post['_files'].
     */
    protected function _getFileData()
    {
        if (isset($_FILES) && is_array($_FILES)) {
            foreach ($_FILES as $name => $data) {
                // sắp xếp lại dữ liệu nếu là multi upload, xem thêm http://php.net/manual/en/features.file-upload.multiple.php
                if (is_array($data['name'])) {
                    $new = array();
                    foreach ($data as $key => $all) {
                        foreach ($all as $i => $val) {
                            $new[$i][$key] = $val;
                        }
                    }
                    $data = $new;
                }

                $this->post['_files'][$name] = $data;
            }
        }
    }

    /**
     * Xóa dữ liệu các biến GLOBAL (post, get, files, request ...)
     */
    protected function _delData()
    {
        unset($_POST);
        unset($_FILES);
        unset($_GET);
        unset($_REQUEST);
    }
}
