<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Event
 * @since Vhmis v2.0
 */
namespace Vhmis\Event;

use SplStack;

/**
 * Class dùng lưu các giá trị trả về khi thực thi Event
 *
 * @category Vhmis
 * @package Vhmis_Event
 */
class Result extends SplStack
{

    protected $_stopped;

    /**
     * Thiết lập dừng
     *
     * @param type $bool            
     * @return \Vhmis\Event\EventResult
     */
    public function setStopped($bool)
    {
        $this->_stopped = $bool;
        
        return $this;
    }

    /**
     * Kiểm tra dừng
     *
     * @return bool
     */
    public function isStoped()
    {
        return $this->_stopped;
    }
}
