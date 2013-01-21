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

namespace Vhmis\Network\Router;

use Vhmis\Config\Configure;

/**
 * Class dùng để xử lý các địa chỉ yêu câu
 * Kiểm tra và phân tích tính hợp lệ của nó từ các địa chí router được khai báo
 * trong file config
 *
 * @category   Vhmis
 * @package    Vhmis_Network
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
     * Controller được yêu cầu
     *
     * @var string
     */
    protected $_controller;

    /**
     * Action yêu cầu
     *
     * @var string
     */
    protected $_action;

    /**
     * Thông số được gửi kèm yêu cầu
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Danh sách các route được khai báo
     *
     * @var array
     */
    protected $_routes = array();

    /**
     * Khởi tạo
     */
    public function __construct()
    {

    }

    /**
     * Khai báo route mặc định
     *
     * @param array $defaultRoute
     * @return \Vhmis\Network\Router
     */
    public function defaultRoute($defaultRoute)
    {
        $this->_app = $defaultRoute['app'];
        $this->_language = $defaultRoute['lang'];
        $this->_controller = $defaultRoute['controller'];
        $this->_action = $defaultRoute['action'];
        $this->_params = $defaultRoute['params'];

        return $this;
    }

    /**
     * Thêm một route vào danh sách route
     *
     * @param mixed $route
     * @return \Vhmis\Network\Router
     */
    public function addRoute($route)
    {
        $this->_routes[] = $route;

        return $this;
    }

    /**
     * Kiểm tra xem địa chỉ có hợp lệ không
     *
     * @param string $uri
     * @return boolean
     */
    public function check($uri)
    {
        return true;
    }

    /**
     * Lấy thông tin yêu cầu sau khi kiểm tra hợp lệ
     */
    public function getAppInfo()
    {
        return;
    }
}
