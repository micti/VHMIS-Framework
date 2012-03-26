<?php

/**
 * Chức năng cơ bản của VHMIS
 *
 * Thiết lập, load các hàm, các lớp, các cấu hình cần thiết để xử lý tất cả request
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

/**
 * DÀNH CHO BẢN ĐANG PHÁT TRIỂN, hiện thị tất cả các lỗi
 */
error_reporting(E_ALL | E_NOTICE);

/**
 * DÀNH CHO BẢN SỬ DỤNG, tắt các hiển thị lỗi
 */
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

/**
 * Thiết lập các đường dẫn, đường dẫn require
 */
define('D_SPEC', DIRECTORY_SEPARATOR);
define('P_SPEC', PATH_SEPARATOR);

define('VHMIS_PATH', dirname(__FILE__));
define('VHMIS_LIBS_PATH', VHMIS_PATH . D_SPEC . 'Libs');
define('VHMIS_CORE_PATH', VHMIS_PATH . D_SPEC . 'Core');
define('VHMIS_VIEW_PATH', VHMIS_PATH . D_SPEC . 'View');
define('VHMIS_COMP_PATH', VHMIS_PATH . D_SPEC . 'Components');
define('VHMIS_SYS_PATH', VHMIS_PATH . D_SPEC . 'System' . D_SPEC . SYSTEM);
define('VHMIS_APPS_PATH', VHMIS_SYS_PATH . D_SPEC . 'Apps');
define('VHMIS_CONF_PATH', VHMIS_SYS_PATH . D_SPEC . 'Config');
define('VHMIS_ZEND_F_PATH', VHMIS_LIBS_PATH . D_SPEC . 'Zend');

// Một số thư viện
set_include_path(VHMIS_LIBS_PATH . D_SPEC . P_SPEC . get_include_path());

/**
 * Gọi file booter.php chứa các hàm cơ bản
 */
require VHMIS_PATH . D_SPEC . 'booter.php';

// Benchmark
$benmark = new Vhmis_Benchmark();
$benmark->timer('start');
Vhmis_Configure::set('Benchmark', $benmark);

/**
 * Cấu hình
 */
$_config = ___loadConfig('Applications', false);
Vhmis_Configure::set('Config', $_config);
$_config = ___loadConfig('Global', false);
Vhmis_Configure::add('Config', $_config);

// Set timezone +7
Vhmis_Date::setTimeZone($_config['timezone']['name']);

/**
 * Lấy uri, xử lý
 */
$_vhmisRequest = new Vhmis_Network_Request();
$_vhmisResponse = new Vhmis_Network_Response();

if($_vhmisRequest->responeCode == '403' || $_vhmisRequest->responeCode == '404')
{
    $_vhmisView = new Vhmis_View();
    ob_start();
    $_vhmisView->renderError('4xx');
    $content = ob_get_clean();

    $_vhmisResponse->body($content);
    $_vhmisResponse->response();
    exit();
}

/**
 * Gọi config của App
 */
$_config = ___loadAppConfig($_vhmisRequest->app['url'], false);
Vhmis_Configure::add('Config', $_config);

/**
 * Gọi Controller
 */
$_vhmisController = ___loadController($_vhmisRequest, $_vhmisResponse);

/**
 * Thực thi chương trình
 */
$_vhmisController->init();