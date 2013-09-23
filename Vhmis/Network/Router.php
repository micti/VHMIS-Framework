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

use Vhmis\Config\Config;
use Vhmis\Config\Configure;

/**
 * Class dùng để xử lý các địa chỉ yêu câu
 * Kiểm tra và phân tích tính hợp lệ của nó từ các địa chí router được khai báo
 * trong file config
 *
 * @category Vhmis
 * @package Vhmis_Network
 * @subpackage Router
 */
class Router
{
    /**
     * Ngôn ngữ được yêu cầu
     *
     * @var string;
     */
    protected $_language;

    /**
     * Ứng dụng được yêu cầu
     *
     * @var string
     */
    protected $_app;

    /**
     * Danh sách các route được khai báo
     *
     * @var array
     */
    protected $_routes = array();

    /**
     * Thông tin ứng với home route
     *
     * @var array
     */
    protected $_homeRoute = array();

    /**
     * Có sử dụng yếu tố ngôn ngữ trong uri không
     *
     * @var boolean
     */
    protected $_useLanguage = false;

    /**
     * Có sử dụng tên app trong uri không
     *
     * @var boolean
     */
    protected $_useApp = true;

    /**
     * Vị trí của yếu tố ngôn ngữ trong uri
     * trước tên app hoặc sau tên app
     *
     * <code>
     * /path/en/forum : trước app
     * /path/forum/en : sau app
     * /path/en : trước app (app không có)
     * </code>
     *
     * @var string
     */
    protected $_positionLanguage = 'beforeappname';

    /**
     * Đường dẫn thư mục gốc web
     *
     * @var string
     */
    protected $_webPath = '';

    /**
     * Đối tượng Route
     *
     * @var \Vhmis\Network\Route
     */
    protected $_route;

    /**
     * Các ngôn ngữ được chấp nhận
     * 
     * @var array
     */
    protected $acceptLanguage = array();

    public function __construct()
    {
        $this->_route = new Route();
    }

    /**
     * Set đường dẫn web chứa trang index.php của hệ thống
     *
     * @param string $path
     * @return \Vhmis\Network\Router
     */
    public function webPath($path)
    {
        $this->_webPath = $path;
        return $this;
    }

    /**
     * Thiết lập các thông tin cơ bản
     *
     * @param bool $useApp Có sử dụng tên app hay không
     * @param bool $useLang Có sử dụng yếu tố ngôn ngữ hay không
     * @param string $positionLang Vị trí của yếu tố
     * @return \Vhmis\Network\Router
     */
    public function setting($useApp, $useLang, $positionLang, $defaultApp, $defaultLanguage, $acceptLanguage)
    {
        $this->_useApp = $useApp;
        $this->_useLanguage = $useLang;
        $this->_positionLanguage = $positionLang;
        $this->_app = $defaultApp;
        $this->_language = $defaultLanguage;
        $this->acceptLanguage = $acceptLanguage;

        return $this;
    }

    /**
     * Khai báo thông tin dành cho route trang chủ
     *
     * @param array $homeRouteInfo
     * @return \Vhmis\Network\Router
     */
    public function homeRoute($homeRouteInfo)
    {
        $this->_homeRouteInfo = $homeRouteInfo;

        $this->_homeRouteInfo['match'] = true;

        return $this;
    }

    /**
     * Lây thông tin ứng với một địa chỉ bất kỳ
     *
     * @param string $uri
     * @return array
     */
    public function check($uri)
    {
        $result = array(
            'match' => false
        );

        // Kiểm tra webpath trong $uri (thực ra là luôn có)
        if ($uri . '/' === $this->_webPath) {
            $uri = $this->_webPath;
        }
        $length = strlen($this->_webPath);
        $found = strpos($uri, $this->_webPath);
        if ($found !== 0) {
            return $result;
        }

        // Xóa webpath trong uri
        $uri = substr($uri, $length);

        // Thêm ký hiệu / ở cuối nếu không có
        $length = strlen($uri);
        if ($uri[$length - 1] !== '/') {
            $uri .= '/';
        }

        // Root
        if ($uri == '/') {
            return $this->_homeRouteInfo;
        }

        // Lấy thông tin app và ngôn ngữ yêu cầu
        if ($this->_useLanguage && $this->_useApp) {
            $uri = explode('/', $uri, 3);
            if (count($uri) != 3)
                return $result;

            $this->_language = $this->_positionLanguage === 'beforeappname' ? $uri[0] : $uri[1];
            $this->_app = $this->_positionLanguage === 'beforeappname' ? $uri[1] : $uri[0];
            $uri = $uri[2];
        } else {
            if ($this->_useLanguage) {
                $uri = explode('/', $uri, 2);
                if (count($uri) != 2)
                    return $result;

                $this->_language = $uri[0];
                $uri = $uri[1];
            } else {
                if ($this->_useApp) {
                    $uri = explode('/', $uri, 2);
                    if (count($uri) != 2)
                        return $result;

                    $this->_app = $uri[0];
                    $uri = $uri[1];
                }
            }
        }

        // Xóa ký tự / thừa ở cuối nếu có
        $length = strlen($uri);
        if ($length >= 1 && $uri[$length - 1] === '/') {
            $uri = substr($uri, 0, $length - 1);
        }

        // Kiểm tra ứng dụng
        $appConfig = Configure::get('ConfigApplications', array());
        if (!isset($appConfig['list']['name'][$this->_app])) {
            return $result;
        }

        // Kiểm tra ngôn ngữ
        if(!isset($this->acceptLanguage[$this->_language])) {
            return $result;
        }

        // Load cấu hình route của ứng dụng
        $routes = Config::appRoutes($this->_app);

        // Đối chiếu với các route, nếu khớp với route nào trước thì ứng với
        // thông tin
        // của route đó
        foreach ($routes as $route) {
            $this->_route->setPattern($route[0])
                ->setController($route[1])
                ->setAction($route[2])
                ->setParams($route[3])
                ->setOutput($route[4])
                ->setRedirect($route[5]);

            $result = $this->_route->check($uri);

            if ($result['match'] === true) {
                $result['app'] = $appConfig['list']['cname'][$this->_app];
                $result['appUrl'] = $this->_app;
                $result['language'] = $this->_language;
                return $result;
            }
        }

        // Nếu không ứng với route nào thì gọi thông tin controller 404;
        return $result;
    }
}
