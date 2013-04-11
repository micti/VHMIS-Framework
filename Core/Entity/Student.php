<?php

class Vhmis_Core_Entity_Student
{

    private $_component = null;

    private $_data;

    /**
     * Khởi tạo đối tượng
     *
     * @param Vhmis_Component_Student $component Đối tượng Vhmis_Component_Student
     * @param array $data Dữ liệu dạng mảng của sinh viên
     */
    public function __construct($component, $data)
    {
        $this->_component = $component;
        $this->_data = $data;
    }

    /**
     * Tìm kiếm các môn học của sinh viên
     */
    public function findSubject()
    {
        return $this->_component->findSubject($this->_data['id']);
    }
}