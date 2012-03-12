<?php

/**
 * Share
 *
 * Xử lý việc share data giữa các app với nhau
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
 * @subpackage    Share
 * @since         1.0.0
 * @license       All rights reversed
 */

abstract class Vhmis_Share
{
    protected $_model; // Hổ trợ tương thích, TODO: delete in ver2
    public $model;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        $this->_loadModel();
    }

    /**
     * Load model của Share
     */
    protected function _loadModel()
    {
        $name = str_replace('Vhmis_Share_', '', get_class($this));
        $db = explode('_', $name, 2);
        $db = Vhmis_Configure::get('Db' . ___fUpper($db[0]));

        $model = 'Vhmis_Model_' . $name;
        $this->model = new $model(array('db' => $db));
        $this->_model = $this->model;
    }
}