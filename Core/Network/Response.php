<?php

use Vhmis\Config\Configure;

/**
 * Response
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem
 * file thông tin đi kèm
 *
 * @copyright Copyright 2011, IT Center, Viethan IT College
 *            (http://viethanit.edu.vn)
 * @link https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category VHMIS
 * @package Core
 * @subpackage Network
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 * Class trả lại kết quả tới client
 *
 * @package Core
 * @subpackage Network
 */
class Vhmis_Network_Response
{

    /**
     * Gửi kết quả xử lý tới client
     */
    public function response()
    {
        // header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        // header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        $this->_sendContent();
    }

    /**
     * Thiết lập nội dung trả về
     *
     * @param
     *            string nội dung trả về
     */
    public function body($content)
    {
        $this->_body = $content;
    }

    /**
     * Thiết lập download
     */
    public function download($filepath, $filename, $filetype = null)
    {
        header('Content-disposition: attachment; filename="' . $filename . '"');
        
        // Xác định file type
        if (! is_string($filetype)) {
            $mines = ___loadConfig('Mine', false);
            $mines = $mines['minetypes'];
            
            $ext = explode('.', $filename);
            $ext = end($ext);
            
            if (isset($mines[$ext])) {
                header('Content-type: ' . $mines[$ext]);
            }
        } else {
            header('Content-type: ' . $filetype);
        }
        
        flush();
        readfile($filepath);
    }

    /**
     * Gửi nội dung trả về
     *
     * @param
     *            string nội dung trả về
     */
    protected function _sendContent($content = '')
    {
        $benmark = Configure::get('Benchmark');
        $body = str_replace('::::xxxxx-memory-xxxx::::', memory_get_usage(), $this->_body);
        echo str_replace('::::xxxxx-time-xxxx::::', $benmark->time('start', 'stop'), $body);
        // echo $this->_body;
        // echo 'bo nho : ' . memory_get_usage();
    }
}