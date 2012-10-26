<?php

/**
 * Employee
 *
 * Truy xuất dữ liệu nhân sự
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
 * @package       Component
 * @subpackage    Entity
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 *
 */
class Vhmis_Component_Employee extends Vhmis_Component
{
    public function init()
    {
        // Kết nối CSDL
        $this->_db('Hrm');
        $db = Vhmis_Configure::get('DbHrm');
        $this->_dbEmployee = new Vhmis_Model_Hrm_Employee(array('db' => $db));
    }

    /**
     * Lấy nhân sự theo id
     *
     * @param int $id Id của nhân sự cần lấy
     * @return mixed Null nếu không có, Vhmis_Core_Entity_Employee nếu có
     */
    public function getById($id)
    {
        $data = $this->_model->find($id);

        if($data != null)
        {
            $data = $this->_model->toArray($data);
            $this->_studentsList['id' . $id] = new Vhmis_Core_Entity_Employee($this, $data);
            $this->_studentsList['code' . $data['code']] = $this->_studentsList['id' . $id];
            return $this->_studentsList['id' . $id];
        }
        else
        {
            return null;
        }
    }
}