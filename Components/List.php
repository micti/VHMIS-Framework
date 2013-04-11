<?php

/**
 * List
 *
 * Truy xuất các danh mục
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
 * @package Component
 * @subpackage Entity
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 */
class Vhmis_Component_List extends Vhmis_Component
{

    private $_shares;

    public function init()
    {
        // Kết nối CSDL
        $this->_db('System');
        
        // Tạo collection cho các Share
        $this->_shares = new Vhmis_Collection_Shares();
    }

    /**
     * Lấy danh sách đơn vị hành chính theo từ khóa
     */
    public function getAdministrativeSubdivision($term)
    {
        // Share của Model System_List_Administrative_Subdivision
        $share = $this->_shares->load('System_List_Administrative_Subdivision');
        
        // Trả về kết quả
        return $share->findByFullName($term);
    }
}