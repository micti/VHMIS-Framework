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

/**
 * Lớp định nghĩa 1 route (định tuyến)
 *
 * @category   Vhmis
 * @package    Vhmis_Network
 * @subpackage Router
 */
class Route implements RouteInterface
{
    /**
     * Các thành phần hay gặp trong 1 uri
     */
    const YEAR = '([12][0-9]{3})';
    const MONTH = '(0[1-9]|1[012])';
    const DAY = '(0[1-9]|[12][0-9]|3[01])';
    const ID = '([0-9]+)';
    const SLUG = '([a-z0-9-]+)';
    const YEARMONTH = '([12][0-9]{3})-(0[1-9]|1[012])';

    /**
     * Link pattern
     *
     * @var string
     */
    protected $_pattern = '';

    /**
     * Link regex
     *
     * @var string
     */
    protected $_regex = '';

    /**
     * Controller
     *
     * @var string
     */
    protected $_controller = '';

    /**
     * Action
     *
     * @var string
     */
    protected $_action = '';

    /**
     * Thông số
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Thông số đi kèm trong pattern
     *
     * @var array
     */
    protected $_paramsInPattern = array();

    /**
     * Thông số
     *
     * @var string
     */
    protected $_redirect = '';

    /**
     * Dạng trả về
     *
     * @var string
     */
    protected $_output = 'html';

    /**
     * Mảng khai báo các kiểu dữ liệu hay dùng trong uri
     *
     * @var array
     */
    protected $_dataTypes = array(
        'year' => self::YEAR,
        'month' => self::MONTH,
        'day' => self::DAY,
        'id' => self::ID,
        'slug' => self::SLUG,
        'monthyear' => self::YEARMONTH
    );

    /**
     * Khởi tạo một đối tượng mới
     *
     * @param array $params Thông số khởi tạo
     *
     */
    public function __construct($params = null)
    {
        if (!is_array($params)) {
            return;
        }

        if (isset($params['pattern'])) {
            $this->setPattern($params['pattern']);
        }

        if (isset($params['controller'])) {
            $this->setController($params['controller']);
        }

        if (isset($params['action'])) {
            $this->setAction($params['action']);
        }

        if (isset($params['params'])) {
            $this->setParams($params['params']);
        }

        if (isset($params['redirect'])) {
            $this->setRedirect($params['redirect']);
        }

        if (isset($params['output'])) {
            $this->setOutput($params['output']);
        }
    }

    /**
     * Thiết lập uri pattern
     *
     * @param string $pattern
     * @return \Vhmis\Network\Route
     */
    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;

        // Tạo link regex khi có pattern
        $this->patternToRegex();

        return $this;
    }

    /**
     * Thiết lập controller
     *
     * @param string $controller
     * @return \Vhmis\Network\Route
     */
    public function setController($controller)
    {
        $this->_controller = $controller;

        return $this;
    }

    /**
     * Thiết lập action
     *
     * @param string $action
     * @return \Vhmis\Network\Route
     */
    public function setAction($action)
    {
        $this->_action = $action;

        return $this;
    }

    /**
     * Thiết lập thông số
     *
     * @param array $params
     * @return \Vhmis\Network\Route
     */
    public function setParams($params)
    {
        foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }

        return $this;
    }

    /**
     * Thiết lập chuyển hướng
     *
     * @param string $redirect
     * @return \Vhmis\Network\Route
     */
    public function setRedirect($redirect)
    {
        $this->_redirect = $redirect;

        return $this;
    }

    /**
     * Thiết lập dạng trả về
     *
     * @param string $output
     * @return \Vhmis\Network\Route
     */
    public function setOutput($output)
    {
        $this->_output = $output;

        return $this;
    }

    /**
     * Xóa hết các thuộc tính của 1 route
     *
     * @return \Vhmis\Network\Route
     */
    public function clear()
    {
        $this->_pattern = '';
        $this->_regex = '';
        $this->_controller = '';
        $this->_action = '';
        $this->_output = 'html';
        $this->_redirect = '';
        $this->_params = array();
        $this->_paramsInPattern = array();

        return $this;
    }

    /**
     * Chuyển link pattern sang dạng link regex để kiểm tra tính hợp lệ
     */
    public function patternToRegex()
    {
        // Lấy thông tin các tham số trong pattern
        // Tham số trong link pattern có dạng [kieuthamso:tenthamso]
        //   Nếu kieuthamso để trống [:tenthamso] thì được xem là kiểu slug
        //   Nếu kieuthamso không chưa được định nghĩa thì được xem là kiểu slug
        $match = preg_match_all('/\[(.*?)\]/', $this->_pattern, $params);

        $regex = array(); // Mảng chứa regex của tham số
        $param = array(); // Mảng chứa tên của tham số

        if ($match >= 1) { // Có tham số
            foreach ($params[1] as $value) {
                $value = explode(':', $value, 2);
                if (count($value) == 2) {
                    if ($value[0] === '') {
                        $regex[] = $this->_dataTypes['slug'];
                    } elseif (isset($this->_dataTypes[$value[0]])) {
                        $regex[] = $this->_dataTypes[$value[0]];
                    } else {
                        $regex[] = $this->_dataTypes['slug'];
                    }

                    $param[] = $value[1];
                }
            }
        }

        // Chuyển link pattern sang link regex
        $this->_regex = str_replace('/', '\\/', $this->_pattern);
        $this->_regex = '/' . str_replace($params[0], $regex, $this->_regex) . '/';
        $this->_paramsInPattern = $param;
    }

    /**
     * Chuyển link redirect sang dạng chính thức
     * Sau khi thay thế giá trị param vào nếu có
     *
     * @param array $params Thông số
     * @return string
     */
    public function makeRedirect($params)
    {
        $redirect = '';

        foreach ($params as $name => $value) {
            $redirect = str_replace('[' . $name . ']', $value, $this->_redirect);
        }

        return $redirect;
    }

    /**
     * Kiểm tra 1 link có hợp lệ với route không
     *
     * @param string $value
     * @return array
     */
    public function check($value)
    {
        $result = array(
            'match' => false
        );

        if (!is_string($value))
            return $result;

        $match = preg_match_all($this->_regex, $value, $params, PREG_SET_ORDER);

        // Không hợp lệ
        if ($match !== 1) { // Chỉ match 1 và chỉ duy nhất 1 lần
            return $result;
        }

        // Hợp lệ
        $result['match'] = true;
        $result['controller'] = $this->_controller;
        $result['output'] = $this->_output;
        $result['action'] = $this->_action;
        $result['params'] = $this->_params;

        // Thiết lập giá trị cho params
        foreach ($this->_paramsInPattern as $key => $name) {
            $result['params'][$name] = $params[0][$key + 1];
        }

        // Lấy chính xác link redirect
        $result['redirect'] = $this->_redirect === '' ? '' : $this->makeRedirect($result['params']);

        return $result;
    }
}
