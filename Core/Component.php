<?php

abstract class Vhmis_Component
{
    private $_controller;

    /**
     * Khởi tạo component, truyền controller vào
     */
    public function __construct($controller)
    {
        $this->_controller = $controller;
        $this->init();
    }

    abstract public function init();

    /**
     * Function kết nối database
     *
     * @param string $database Tên database
     */
    protected function _db($name)
    {
        return $this->_controller->_db($name);
    }
}
?>