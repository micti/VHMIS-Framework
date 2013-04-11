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
 * Interface cho lớp định nghĩa 1 route (định tuyến)
 *
 * @category Vhmis
 * @package Vhmis_Network
 * @subpackage Router
 */
interface RouteInterface
{

    /**
     * Khởi tạo một đối tượng mới
     *
     * @param array $params
     *            Thông số khởi tạo
     */
    public function __construct($params);

    /**
     * Thiết lập uri pattern
     *
     * @param string $pattern            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setPattern($pattern);

    /**
     * Thiết lập controller
     *
     * @param string $controller            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setController($controller);

    /**
     * Thiết lập action
     *
     * @param string $action            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setAction($action);

    /**
     * Thiết lập thông số
     *
     * @param array $params            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setParams($params);

    /**
     * Thiết lập chuyển hướng
     *
     * @param string $redirect            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setRedirect($redirect);

    /**
     * Thiết lập dạng trả về
     *
     * @param string $output            
     * @return \Vhmis\Network\RouteInterface
     */
    public function setOutput($output);

    /**
     * Xóa hết các thuộc tính của 1 route
     *
     * @return \Vhmis\Network\RouteInterface
     */
    public function clear();

    /**
     * Chuyển link pattern sang dạng link regex để kiểm tra tính hợp lệ
     */
    public function patternToRegex();

    /**
     * Chuyển link redirect sang dạng chính thức
     * Sau khi thay thế giá trị param vào nếu có
     *
     * @param array $params
     *            Thông số
     * @return string
     */
    public function makeRedirect($params);

    /**
     * Kiểm tra 1 link có hợp lệ với route không
     *
     * @param string $value            
     * @return array
     */
    public function check($value);
}
