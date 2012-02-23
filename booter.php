<?php

/**
 * Chức năng cơ bản của VHMIS
 *
 * Chứa các hàm để gọi file, gọi class và một số hàm thực hiện các việc cơ bản khác
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
 * Thiết lập các hằng số liên quan đến lỗi
 */
define('VHMIS_ERROR_MYSQL', '-99999999');
define('VHMIS_ERROR_LOGINSESSION', '-99999998');
define('VHMIS_ERROR_STOP', '-99999997');
define('VHMIS_ERROR_PAGENOTFOUND', '-99999996');
define('VHMIS_ERROR_PERMISSION', '-99999995');

/**
 * Hàm gọi class tự động (lazy load)
 *
 * @param string $name Tên class
 */
function __autoLoad($class)
{
    if(class_exists($class)) return;

    $name = explode('_', $class);

    if($name[0] == 'Zend') ___loadZendClass($class);
    if($name[0] == 'Vhmis')
    {
        if(isset($name[2]) && $name[2] != '')
        {
            if($name[1] == 'Component') ___loadComponentClass($class);
            elseif($name[1] == 'Model') ___loadModelClass(str_replace('Vhmis_Model_', '', $class));
            elseif($name[1] == 'Share') ___loadShareClass(str_replace('Vhmis_Share_', '', $class));
            else ___loadCoreClass($class);
        }
        else ___loadCoreClass($class);
    }
}

/**
 * Gọi file chứa class Core
 *
 * @param string $name Tên class
 */
function ___loadCoreClass($name)
{
    if(class_exists($name)) return;

    // Tên class Vhmis_Uri_Pattern -> Thư mục Core/Uri/Pattern.php
    $name = explode('_', $name);
    $count = count($name);
    $path = '';

    for($i = 1; $i < $count - 1; $i++)
    {
        $path .= D_SPEC . ___fUpper($name[$i]);
    }

    ___loadFile(___fUpper($name[$count-1]) . '.php', VHMIS_CORE_PATH . D_SPEC . $path);
}

/**
 * Gọi file chứa class Zend Framework
 *
 * @param string $name Tên class
 */
function ___loadZendClass($name)
{
    if(class_exists($name)) return;

    $name = explode('_', $name);
    $count = count($name);
    $path = '';

    for($i = 1; $i < $count - 1; $i++)
    {
        $path .= D_SPEC . ___fUpper($name[$i]);
    }

    ___loadFile(___fUpper($name[$count-1]) . '.php', VHMIS_ZEND_F_PATH . D_SPEC . $path);
}

/**
 * Gọi file chứa class Component
 *
 * @param string $component Tên component cần gọi
 */
function ___loadComponentClass($component)
{
    if(class_exists($component)) return;

    $component = str_replace('Vhmis_Component_', '', $component);

    ___loadFile(___fUpper($component . '.php'), VHMIS_COMP_PATH);
}

/**
 * Gọi file chứa class Model
 *
 * @param string $model Tên model
 */
function ___loadModelClass($model)
{
    if(class_exists('Vhmis_Model_' . $model)) return;

    $model = explode('_', $model, 2);

    ___loadFile($model[1] . '.php', VHMIS_APPS_PATH . D_SPEC . ___fUpper($model[0]) . D_SPEC . 'Model');
}

/**
 * Gọi file chứa class Share của app
 *
 * @param string $data Tên share
 */
function ___loadShareClass($data)
{
    if(class_exists('Vhmis_Share_' . $data)) return;

    $data = explode('_', $data, 2);

    ___loadFile($data[1] . '.php', VHMIS_APPS_PATH . D_SPEC . ___fUpper($data[0]) . D_SPEC . 'Share');
}

/**
 * Gọi file
 *
 * @param string $filename Tên file
 * @param string $path Đường dẫn của file
 * @param boolean $once Kiểm tra xem file đã gọi hay chưa
 */
function ___loadFile($filename, $path, $once = false)
{
    if($once == true) include_once $path . D_SPEC . $filename;
    else include $path . D_SPEC . $filename;
}

/**
 * Gọi đối tượng Controller
 *
 * @param $appInfo Thông tin app (thông tin request)
 * @return VHMIS_CONTROLLER Đối tượng mở rộng của VHMIS_CONTROLLER ứng với request
 */
function ___loadController($request, $response)
{
    $controllerName = 'Vhmis_Controller_' . ___fUpper($request->app['app']) . '_' . $request->app['info']['controller'];
    $path           = VHMIS_APPS_PATH . D_SPEC . ___fUpper($request->app['app']) . D_SPEC . 'Controller';
    $file           = $request->app['info']['controller'] . '.php';

    ___loadFile($file, $path);

    return new $controllerName($request, $response);
}

/**
 * Hàm gọi file Config của các ứng dụng
 *
 * @param string $appInfo Tên url của ứng dụng hoặc biến chứa thông tin ứng dụng
 * @param boolean $store Thiết lập có lưu vào $_vhmisConfigAll ko, mặc định là true
 * @return void|array Nếu $store = true, config được được load và lưu vào $_vhmisConfigAll, nếu $store = false sẽ trả về kết quả config được load
 */
function ___loadAppConfig($appInfo, $store = true)
{
    global $_vhmisConfigAll;

    if(is_array($appInfo)) $appInfo = $appInfo['url'];

    $appInfo = strtolower($appInfo);

    require VHMIS_APPS_PATH . D_SPEC . ___fUpper($appInfo) . D_SPEC . 'Config' . D_SPEC . 'Config.php';

    if($store === true) {
        if(!isset($_vhmisConfigAll['apps']['info'][$appInfo])) $_vhmisConfigAll = array_merge_recursive($_vhmisConfigAll, $_vhmisConfig);
    }
    else return $_vhmisConfig;
}

/**
 * Hàm gọi file Config
 *
 * @param string $name Tên config cần gọi
 * @param boolean $store Thiết lập có lưu vào $_vhmisConfigAll ko, mặc định là true
 * @return void|array Nếu $store = true, config được được load và lưu vào $_vhmisConfigAll, nếu $store = false sẽ trả về kết quả config được load
 */
function ___loadConfig($name, $store = true)
{
    global $_vhmisConfigAll;

    require VHMIS_CONF_PATH . D_SPEC . ___fUpper($name . '.php');

    if($store === true) $_vhmisConfigAll = array_merge_recursive($_vhmisConfigAll, $_vhmisConfig);
    else return $_vhmisConfig;
}

/**
 * Hàm kiểm tra tên app
 *
 * @param string $app Tên ứng dụng dạng url (thường rút gọn, ko viết hoa, sử dụng ở url hoặc đặc tên biến, index mảng)
 * @return boolean|string Sai nếu ko có apps, nếu có trả về tên App
 */
function ___checkApp($app)
{
    $config = ___loadConfig('Applications', false);

    if(!in_array($app, $config['apps']['list']['url']))
    {
        return false;
    }

    return $config['apps']['list']['name'][$app];
}

/**
 * Hàm viết hoa chữ cái đầu tiên (non-unicode)
 *
 * @param string $string Chuỗi đưa vào
 * @return string
 */
function ___fUpper($string)
{
    return ucfirst(strtolower($string));
}

/**
 * Hàm chuyển tên class sang tên biến
 * Dạng Abc_Chakf_Chghfh thành abcChakfChghfh
 *
 * @param string $string Chuỗi vào
 * @return string
 */
function ___ctv($string)
{
    return lcfirst(str_replace('_', '', $string));
}

/**
 * Hàm bỏ ký tự \ cho dữ liệu mảng một hay nhiều chiều
 *
 * @param array $values mảng cần thực hiện việc loại bỏ
 * @return array mảng sau khi được loại bỏ
 */
function ___stripSlashes($values)
{
    if(is_array($values))
    {
        foreach ($values as $key => $value)
        {
            $values[$key] = ___stripSlashes($value);
        }
    } else {
        $values = stripslashes($values);
    }

    return $values;
}

/**
 * Kết nối database
 */
function ___connectDb($config, $type = 'Pdo_Mysql')
{
    try
    {
        //echo '2';
        $db = Zend_Db::factory($type, $config);
        $db->getConnection();
        return $db;
    }
    catch (Zend_Db_Adapter_Exception $e)
    {
        return false;
    }
    catch (Zend_Db_Exception $e)
    {
        return false;
    }
}