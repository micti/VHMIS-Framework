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

/**
 * Class dùng để mô tả các sự kiện
 *
 * @category Vhmis
 * @package Vhmis_Event
 */
class Event implements EventInterface
{

    /**
     * Tên của sự kiện
     *
     * @var string
     */
    protected $_name;

    /**
     * Đối tượng xảy ra sự kiện
     *
     * @var mixed
     */
    protected $_target;

    /**
     * Các tham số truyền theo
     *
     * @var array
     */
    protected $_params;

    /**
     * Dừng lại
     *
     * @var bool
     */
    protected $_stop = false;

    /**
     * Lấy tên gọi của sự kiện
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Lấy đối tượng xảy ra sự kiện
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->_target;
    }

    /**
     * Lấy các tham số truyền theo
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Thiết lập tên gọi của sự kiện
     *
     * @param string $name
     * @return \Vhmis\Event\Event
     */
    public function setName($name)
    {
        $this->_name = $name;
        
        return $this;
    }

    /**
     * Thiết lập đối tượng xảy ra sự kiện
     *
     * @param mixed $target
     * @return Vhmis\Event\Event
     */
    public function setTarget($target)
    {
        $this->_target = $target;
        
        return $this;
    }

    /**
     * Thiết lập các tham số truyền theo
     *
     * @param array $params
     * @return Vhmis\Event\EventInterface
     */
    public function setParams($params)
    {
        $this->_params = $params;
        
        return $this;
    }

    /**
     * Thiết lập dừng lại
     *
     * @param bool $bool
     */
    public function setStopPropagation($bool = false)
    {
        $this->_stop = $bool;
    }

    /**
     * Kiểm tra xem có phải dừng lại không
     *
     * @param bool $bool
     */
    public function isPropagationStopped()
    {
        return $this->_stop;
    }
}
