<?php

/**
 * Request
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
 * @subpackage    Network
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 * Class lấy thông tin của request gửi đến hệ thống.
 *
 * @package       Core
 * @subpackage    Network
 */
class Vhmis_Network_Request
{
    /**
     * Đối tượng của VHMIS_URI_ANALYZE
     *
     * @var Vhmis_Uri_Analyze
     */
    protected $_uriAnalyze;

    /**
     * Đối tượng của VHMIS_URI
     *
     * @var Vhmis_Uri
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
     */
    public $responeCode;

    /**
     * Khởi tạo!
     *
     * @param string $url Địa chỉ url.
     */
    public function __construct($url = null)
    {
        $this->responeCode = '200';

        $config = Vhmis_Configure::get('Config');

        if(empty($url))
        {
            $url = $this->url();
        }

        $this->_uri = new Vhmis_Uri($url);

        if($this->_uri->valid() == false)
        {
            $this->responeCode = '403';
            return;
        }

        $this->_uriAnalyze = new Vhmis_Uri_Analyze($this->_uri->getPath(), $config['site']['path'], $config['apps']['indexAppInfo']);

        $this->app = $this->_uriAnalyze->getAppInfo();

        if($this->app == false)
        {
            $this->responeCode = '404';
            return;
        }

        $this->_getPostData();
        $this->_getGetData();
        $this->_getFileData();
        $this->_delData();
    }

    /**
     * Lấy địa chỉ ip
     *
     * @return string Địa chỉ ip.
     */
    public function realIp()
    {
        $ip = '0.0.0.0';

        if(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if(($commaPos = strpos($ip, ',')) > 0)
        {
            $ip = substr($ip, 0, ($commaPos - 1));
        }

        return $ip;
    }

    /**
     * Lấy địa chỉ url hiện tại.
     *
     * @return Địa chỉ url hiện tại.
     */
    public function url()
    {
        return (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']
                                           : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * Lấy địa chỉ referrer
     *
     * @return Địa chỉ referrer
     */
    public function referrer()
    {
        $referrer = $_SERVER['HTTP_REFERER'];
        $forward = $_SERVER['HTTP_X_FORWARDED_HOST'];

        if($forward) return $forward;
        return $referrer;
    }

    /**
     * Lấy dữ liệu của phương thức POST.
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở $this->data.
     */
    protected function _getPostData()
    {
        $this->post = $_POST;

        // Loại bỏ ký tự \
        if(ini_get('magic_quotes_gpc') === '1')
        {
            $this->post = ___stripSlashes($this->post);
        }
    }

    /**
     * Lấy dữ liệu của phương thức GET (querystring).
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở $this->query.
     */
    protected function _getGetData()
    {
        $this->get = $_GET;

        // Loại bỏ ký tự \
        if(ini_get('magic_quotes_gpc') === '1')
        {
            $this->get = ___stripSlashes($this->get);
        }
    }

    /**
     * Lấy dữ liệu của $_FILE.
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở $this->post['_files'].
     */
    protected function _getFileData()
    {
        if(isset($_FILES) && is_array($_FILES))
        {
            foreach($_FILES as $name => $data)
            {
                // sắp xếp lại dữ liệu nếu là multi upload, xem thêm http://php.net/manual/en/features.file-upload.multiple.php
                if(is_array($data['name']))
                {
                    $new = array();
                    foreach($data as $key => $all){
                        foreach($all as $i => $val)
                        {
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
     * Xóa dữ liệu các biến global
     */
    protected function _delData()
    {
        unset($_POST);
        unset($_FILES);
        unset($_GET);
        unset($_REQUEST);
    }
}