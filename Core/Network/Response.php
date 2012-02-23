<?php

/**
 * Response
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
 * Class trả lại kết quả tới client
 *
 * @package       Core
 * @subpackage    Network
 */
class Vhmis_Network_Response
{
    /**
     * Gửi kết quả xử lý tới client
     */
    public function response()
    {
        $this->_sendContent();
    }

    /**
     * Thiết lập nội dung trả về
     *
     * @param string nội dung trả về
     */
    public function body($content)
    {
        $this->_body = $content;
    }

    /**
     * Gửi nội dung trả về
     *
     * @param string nội dung trả về
     */
    protected function _sendContent($content = '')
    {
        echo $this->_body;
        //echo 'bo nho : ' . memory_get_usage();
    }
}