<?php

use Vhmis\Config\Configure;

/**
 * Log
 *
 * Thực hiện lưu vết
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
 * @package       Components
 * @subpackage    Log
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 *
 */
class Vhmis_Component_Log extends Vhmis_Component
{
    protected $_dbLog;

    public function init()
    {
        // Kết nối CSDL
        $this->_db('System');
        $db = Configure::get('DbSystem');
        $this->_dbLog = new Vhmis_Model_System_Log(array('db' => $db));
    }

    public function insert($uid, $username, $app, $message, $type, $more)
    {
        $log = $this->_dbLog->fetchNew();

        $log->uid = $uid;
        $log->username = $username;
        $log->app = $app;
        $log->message = $message;
        $log->type = $type;
        $log->more = $more;
        $log->time = date('Y-m-d H:i:s');
        $log->ip = '127.0.0.1';

        $log->save();
    }
}