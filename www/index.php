<?php

/**
 * Chức năng cơ bản của VHMIS
 *
 * File index của website, toàn bộ request, đường dẫn ảo, đều được file index sử lý, file này sẽ gọi file boot.php của framework
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
 * @package       Loader
 * @since         1.0.0
 * @license       All rights reversed
 */

// Khai báo đường dẫn Framework
DEFINE('VHMIS', 'BUILD');

// Khai báo tên hệ thống
DEFINE('SYSTEM', 'SNAME');

// Khai báo đường dẫn web
DEFINE('PATH', __DIR__);

// Gọi file boot.php của framework
require VHMIS . 'boot.php';
