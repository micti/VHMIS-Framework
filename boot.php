<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Boot
 * @since Vhmis v1.0
 */

/**
 * Thiết lập các đường dẫn, đường dẫn require
 */
define('D_SPEC', DIRECTORY_SEPARATOR);

define('VHMIS_LIBS_PATH', VHMIS_PATH . D_SPEC . 'Libs');
define('VHMIS_CORE_PATH', VHMIS_PATH . D_SPEC . 'Vhmis');
define('VHMIS_APPS_PATH', VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps');
define('VHMIS_SYS_CONF_PATH', VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Config');

// Autoload
include 'autoload.php';

// Thiết lập mã lỗi
define('VHMIS_ERROR_DATABASE', '-99999999');
define('VHMIS_ERROR_LOGINSESSION', '-99999998');
define('VHMIS_ERROR_STOP', '-99999997');
define('VHMIS_ERROR_PAGENOTFOUND', '-99999996');
define('VHMIS_ERROR_NOTPERMISSION', '-99999995');
define('VHMIS_ERROR_ACTIONMISSING', '-99999994');

// Benchmark
$benmark = new \Vhmis\Benchmark\Benchmark();
$benmark->timer('start');
\Vhmis\Config\Configure::set('Benchmark', $benmark);

new \Vhmis\Application\App();
