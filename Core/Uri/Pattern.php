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
 * @copyright Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category VHMIS
 * @package Core
 * @subpackage URI
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 * Đối tượng cấu trúc link, URL_PATTERN
 */
class Vhmis_Uri_Pattern
{

    /**
     * Các hằng số chứa biểu thức điều kiện để kiểm tra tên của các thành phần
     * hay gặp trong mẫu link
     */
    const YEAR = '[12][0-9]{3}';

    const MONTH = '0[1-9]|1[012]';

    const DAY = '0[1-9]|[12][0-9]|3[01]';

    const ID = '[0-9]+';

    const SLUG = '[a-z0-9-]+';

    const YEARMONTH = '([12][0-9]{3})-(0[1-9]|1[012])';

    /**
     * Mảng chứa tên của các kiểu dữ liệu và biểu thức điều kiện để kiểm tra
     */
    protected $_dataTypes = array(
        'year' => VHMIS_URI_PATTERN::YEAR,
        'month' => VHMIS_URI_PATTERN::MONTH,
        'day' => VHMIS_URI_PATTERN::DAY,
        'id' => VHMIS_URI_PATTERN::ID,
        'slug' => VHMIS_URI_PATTERN::SLUG,
        'monthyear' => VHMIS_URI_PATTERN::YEARMONTH
    );

    /**
     * Mẫu link
     *
     * @var string
     */
    protected $_pattern;

    /**
     * Controller
     *
     * @var string
     */
    protected $_controller;

    /**
     * Action, hành động
     *
     * @var string
     */
    protected $_action;

    /**
     * Thông số kèm theo
     *
     * @var array
     */
    protected $_params;

    /**
     * Kiểu xuất ra của View
     *
     * @var array
     */
    protected $_output;

    /**
     * Địa chỉ chuyển hướng
     *
     * @var string
     */
    protected $_redirect;

    /**
     * Thiết lập thông tin mẫu link
     *
     * @param array $patternInfo
     *            Thông tin mẫu link
     */
    public function setPatternInfo($patternInfo)
    {
        $this->_pattern = $patternInfo[0];
        $this->_controller = $patternInfo[1];
        $this->_action = $patternInfo[2];
        $this->_params = $patternInfo[3];
        $this->_output = $patternInfo[4];
        $this->_redirect = $patternInfo[5];
    }

    /**
     * Kiểm tra một URI có hợp lệ với đối tượng cấu trúc link
     *
     * @param
     *            string địa chỉ cần đối chiếu với mẫu link
     * @return array kết quả của việc đối chiếu
     */
    public function validateURI($uri)
    {
        $segment = explode("/", $this->_pattern);
        $total = count($segment);
        $uriSegment = explode("/", $uri, $total);
        $redirect = $this->_redirect;
        
        // Kết quả mặc định
        $result['valid'] = false;
        $result['controller'] = '';
        $result['action'] = '';
        $result['params'] = '';
        $result['output'] = '';
        $result['redirect'] = '';
        
        // Địa chỉ cần đối chiếu ko đủ số segment so với mẫu
        if ($total > count($uriSegment)) {
            return $result;
        }
        
        // Kiểm tra segment cuối cùng, chỉ hợp lệ nếu ko có ký tự / hoặc có ký
        // tự / nằm cuối cùng
        $lastSegment = explode('/', $uriSegment[$total - 1], 2);
        
        if (isset($lastSegment[1]) and $lastSegment[1] != '') {
            
            return $result;
        } else {
            // Xóa bỏ ký tự / nằm cuối
            $uriSegment[$total - 1] = $lastSegment[0];
        }
        
        // Kiểm tra từng segment của địa chỉ với từng segment của link mẫu, nếu
        // ko khớp với 1 chổ bất kỳ thì sai
        for ($i = 0; $i < $total; $i++) {
            if ($segment[$i] != $uriSegment[$i]) {
                // Kiem tra xem co phai la param dang type:name
                $paramInfo = explode(':', $segment[$i], 2);
                
                // Neu dung, them param nay vao
                if (isset($paramInfo[1]) && $paramInfo[1] != '' && $this->_validate($paramInfo[0], $uriSegment[$i])) {
                    $params[$paramInfo[1]] = $uriSegment[$i];
                    
                    // Neu co dia chi redirect, thu thay the param nay
                    if ($redirect != '') {
                        $redirect = str_replace($paramInfo[0] . ':' . $paramInfo[1], $uriSegment[$i], $redirect);
                    }
                }                 // Sai, tra ve ket qua sai mac dinh
                else {
                    return $result;
                }
            }
        }
        
        // Dia chi hop le, tra ve ket qua
        $result['valid'] = true;
        $result['controller'] = $this->_controller;
        $result['action'] = $this->_action;
        $result['output'] = $this->_output;
        $result['redirect'] = $redirect;
        if (isset($params) && is_array($params)) {
            if (is_array($this->_params)) {
                $result['params'] = array_merge($this->_params, $params);
            } else {
                $result['params'] = $params;
            }
        } else {
            $result['params'] = $this->_params;
        }
        
        return $result;
    }

    /**
     * Hàm dùng để kiểm tra dữ liệu ở link có thuộc loại dữ liệu đã quy định ở
     * link mẫu hay không
     *
     * @param string $type
     *            Kiểu dữ liệu quy định
     * @param string $data
     *            Dữ liệu cần kiểm tra
     * @return boolean
     */
    protected function _validate($type, $data)
    {
        if (!isset($this->_dataTypes[$type]))
            return false;
        
        $found = preg_match('/' . $this->_dataTypes[$type] . '/', $data, $match);
        
        if ($found == 0 || $match[0] != $data)
            return false;
        else
            return true;
    }
}