<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link       http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright  Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @package    Vhmis_Boot
 * @since      Vhmis v1.0
 */

use Vhmis\Config\Configure;

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
define('VHMIS_CORE2_PATH', VHMIS_PATH . D_SPEC . 'CoreVer2');
define('VHMIS_VIEW_PATH', VHMIS_PATH . D_SPEC . 'View');
define('VHMIS_COMP_PATH', VHMIS_PATH . D_SPEC . 'Components');
define('VHMIS_SYS_PATH', VHMIS_PATH . D_SPEC . 'System' . D_SPEC . SYSTEM);
define('VHMIS_APPS_PATH', VHMIS_SYS_PATH . D_SPEC . 'Apps');
define('VHMIS_CONF_PATH', VHMIS_SYS_PATH . D_SPEC . 'Config');
define('VHMIS_ZEND_F_PATH', VHMIS_LIBS_PATH . D_SPEC . 'Zend');
define('VHMIS_DOCTRINE_PATH', '/WebServer');

// Một số thư viện
set_include_path(VHMIS_LIBS_PATH . D_SPEC . P_SPEC . get_include_path());

/**
 * Gọi file booter.php chứa các hàm cơ bản
 */
require VHMIS_PATH . D_SPEC . 'booter.php';

/**
 * Auto load cho Doctrine
 */
require VHMIS_DOCTRINE_PATH . '/Doctrine/ORM/Tools/Setup.php';
Doctrine\ORM\Tools\Setup::registerAutoloadDirectory(VHMIS_DOCTRINE_PATH);

// Benchmark
$benmark = new Vhmis_Benchmark();
$benmark->timer('start');
Configure::set('Benchmark', $benmark);

/**
 * Cấu hình
 */
$_config = ___loadConfig('Applications', false);
Configure::set('Config', $_config);
$_config = ___loadConfig('Global', false);
Configure::add('Config', $_config);

// Set timezone +7
Vhmis_Date::setTimeZone($_config['timezone']['name']);

// Ngôn ngữ
Configure::set('Locale', $_config['locale']['lang'] . '_' . $_config['locale']['region']);

/**
 * Lấy uri, xử lý
 */
$_vhmisRequest = new Vhmis_Network_Request();
$_vhmisResponse = new Vhmis_Network_Response();

if ($_vhmisRequest->responeCode == '403' || $_vhmisRequest->responeCode == '404')
{
    $_vhmisView = new Vhmis_View();
    $_vhmisView->transferConfigData(Configure::get('Config'));
    ob_start();
    $_vhmisView->renderError('4xx');
    $content = ob_get_clean();

    // need rewrite;
    header('HTTP/1.1 404 Not Found');

    $_vhmisResponse->body($content);
    $_vhmisResponse->response();
    exit();
}

/**
 * Chuyển hướng
 */
if(is_string($_vhmisRequest->app['info']['redirect']) && $_vhmisRequest->app['info']['redirect'] !== '')
{
    // To do : cần viết lại đoạn này

    header('Location: ' . $_config['site']['path'] . $_vhmisRequest->app['info']['redirect']);
    exit();
}

/**
 * Gọi config của App
 */
$_config = ___loadAppConfig($_vhmisRequest->app['url'], false);
Configure::add('Config', $_config);

/**
 * Gọi Controller
 */
$_vhmisController = ___loadController($_vhmisRequest, $_vhmisResponse);

/**
 * Thực thi chương trình
 */
$_vhmisController->init();
