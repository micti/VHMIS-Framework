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
 * Đối tượng cấu trúc link, Uri_Analyze
 */
class Vhmis_Uri_Analyze
{
    protected $_uriPattern;
    /**
     * Khởi tạo
     *
     * @param string $link URI yêu cầu
     * @param string $sitePath Đường dẫn của site
     * @param array $indexAppInfo Thông tin ứng dụng ứng với trang chủ
     */
    public function __construct($link, $sitePath, $indexAppInfo)
    {
        $this->_link = $link;

        // Xóa địa chỉ thực
        $link = explode($sitePath, $this->_link, 2);
        $this->_link = (!isset($link[1])) ? '' : $link[1];

        $this->_uriPattern = new Vhmis_Uri_Pattern();

        // Lấy thông tin của request
        if($this->_link == '')
        {
            $this->_appInfo = $indexAppInfo;
        }
        else
        {
            $this->_appInfo = $this->_analyze();
        }
    }

    protected function _analyze()
    {
        // Tách app và link của app
        $app = explode('/', $this->_link, 2);
        $appLink = isset($app[1]) ? $app[1] : ''; // Trường hợp trang chủ của app, http://domain/path/app
        $appUrlName = $app[0];

        // Kiểm tra app
        $app = ___checkApp($appUrlName);

        if($app === false)
        {
            return false;
        }

        $config = ___loadAppConfig($app, false);

        // Kiểm tra link của app
        $appLinkInfo['valid'] = false;

        foreach($config['apps']['info'][$appUrlName]['patterns'] as $pattern)
        {
            $this->_uriPattern->setPatternInfo($pattern);
            $appLinkInfo = $this->_uriPattern->validateURI($appLink);
            if($appLinkInfo['valid'] === true) break;
        }

        if($appLinkInfo['valid'] === false) return false;
        else
        {
            return array(
                'app'    => $app,
                'url' => $appUrlName,
                'info'   => $appLinkInfo
            );
        }
    }

    public function getAppInfo()
    {
        return $this->_appInfo;
    }
}