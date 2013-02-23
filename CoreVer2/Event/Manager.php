<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link       http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright  Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @package    Vhmis_Event
 * @since      Vhmis v2.0
 */

namespace Vhmis\Event;

/**
 * Quản lý các sự kiện
 *
 * @category Vhmis
 * @package Vhmis_Event
 */
class Manager
{
    /**
     * Mảng chứa thông tin các sự kiện
     *
     * @var array
     */
    protected $_events;

    /**
     * Thực thi sự kiện
     *
     * @param string $name
     * @param mixed $target
     * @param array $params
     * @return array
     */
    public function trigger($name, $target, $params)
    {
        $event = new Event();
        $event->setName($name)->setTarget($target)->setParams($params);
        $result = array();

        if (isset($this->_events[$name])) {
            foreach ($this->_events[$name] as $listener) {
                $result[] = $listener['callback']($event);
            }
        }

        return $result;
    }

    /**
     * Gắn listener vào sự kiện
     *
     * @param type $name Tên của sự kiện
     * @param type $callback Callback
     */
    public function attach($name, $callback)
    {
        $this->_events[$name][] = array(
            'callback' => $callback
        );
    }

    /**
     * Xóa các listerner ra khỏi sự kiện
     *
     * @param string $name
     */
    public function detach($name)
    {
        unset($this->_events[$name]);
    }
}
