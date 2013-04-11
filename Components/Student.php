<?php
use Vhmis\Config\Configure;

/**
 * Student
 *
 * Truy xuất dữ liệu sinh viên
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
 * @package Component
 * @subpackage Entity
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 */
class Vhmis_Component_Student extends Vhmis_Component
{

    public function init()
    {
        // Kết nối CSDL
        $this->_db('DbStudent');
        
        $db = Configure::get('DbStudent');
        $this->_model = new Vhmis_Model_Student_Student(array(
            'db' => $db
        ));
    }

    /**
     * Lấy sinh viên theo id
     *
     * @param int $id Id của sinh viên cần lấy
     * @return mixed Null nếu không có, Vhmis_Core_Entity_Student nếu có
     */
    public function getById($id)
    {
        $data = $this->_model->find($id);
        
        if ($data != null) {
            $data = $this->_model->toArray($data);
            $this->_studentsList['id' . $id] = new Vhmis_Core_Entity_Student($this, $data);
            $this->_studentsList['code' . $data['code']] = $this->_studentsList['id' . $id];
            return $this->_studentsList['id' . $id];
        } else {
            return null;
        }
    }
}