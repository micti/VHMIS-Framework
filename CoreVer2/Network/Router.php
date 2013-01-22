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
    protected $_useAppName = true;

    /**
     * Vị trí của yếu tố ngôn ngữ trong uri
     * trước tên app hoặc sau tên app
     *
     * <code>
     *     /path/en/forum : trước app
     *     /path/forum/en : sau app
     *     /path/en       : trước app (app không có)
     * </code>
     *
     * @var string
     */
    protected $_positionLanguage = 'beforeappname';

    /**
     * Đối tượng Route
     *
     * @var \Vhmis\Network\Route
     */
    protected $_route;

    public function __construct()
    {
        $this->_route = new Route();
    }

    /**
     * Thiết lập các thông tin cơ bản
     *
     * @param bool $useApp Có sử dụng tên app hay không
     * @param bool $useLang Có sử dụng yếu tố ngôn ngữ hay không
     * @param string $positionLang Vị trí của yếu tố
     */
    public function setting($useApp, $useLang, $positionLang)
    {
        $this->_useAppName = $useApp;
        $this->_useLanguage = $useLang;
        $this->_positionLanguage = $positionLang;
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
     * Thêm một route vào danh sách route
     *
     * <code>
     *    $route = array(
     *        'pattern' => 'blog/view/[id:postid].html',
     *        'controller' => 'Blog',
     *        'action' => 'View',
     *        'params' => array(),
     *        'output'   => 'html'
     *    );
     * </code>
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
     * Lây thông tin ứng với một địa chỉ bất kỳ
     *
     * @param string $uri
     * @return array
     */
    public function check($uri)
    {
        $this->_language = '';
        $this->_app = '';

        // Xóa ký hiệu / ở cuối nếu có
        $length = strlen($uri);
        if ($uri[$length - 1] == '/')
            $uri = substr($uri, 0, $length - 1);

        // Root
        if ($uri == '')
            return $this->_homeRouteInfo;

        // Lấy thông tin app và ngôn ngữ yêu cầu
        if ($this->_language && $this->_useAppName) {
            $uri = explode('/', $uri, 3);

            $this->_language = $this->_positionLanguage === 'beforeappname' ? $uri[0] : $uri[1];
            $this->_app = $this->_positionLanguage === 'beforeappname' ? $uri[1] : $uri[0];
            $uri = $uri[2];
        } else if ($this->_language) {
            $uri = explode('/', $uri, 2);

            $this->_language = $uri[0];
            $uri = $uri[1];
        } else if ($this->_useAppName) {
            $uri = explode('/', $uri, 2);

            $this->_app = $uri[0];
            $uri = $uri[1];
        }

        // Đối chiếu với các route, nếu khớp với route nào trước thì ứng với thông tin
        // của route đó
        foreach($this->_routes as $route)
        {
            $this->_route->setPattern($route[0])->setController($route[1])->setAction($route[2])
                    ->setParams($route[3])->setOutput($route[4])->setRedirect($route[5]);

            $result = $this->_route->check($uri);

            if($result['match'] === true)
            {
                return $result;
            }
        }

        // Nếu không ứng với route nào thì gọi thông tin controller 404;
        return array('match' => false);
    }
}
