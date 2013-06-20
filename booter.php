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
 * Thiết lập các hằng số liên quan đến lỗi
 */
define('VHMIS_ERROR_DATABASE', '-99999999');
define('VHMIS_ERROR_LOGINSESSION', '-99999998');
define('VHMIS_ERROR_STOP', '-99999997');
define('VHMIS_ERROR_PAGENOTFOUND', '-99999996');
define('VHMIS_ERROR_NOTPERMISSION', '-99999995');
define('VHMIS_ERROR_ACTIONMISSING', '-99999994');

/**
 * Hàm gọi class tự động (lazy load)
 *
 * @param string $name Tên class
 */
function ___autoLoad($class)
{
    ___loadCoreClassWithNamespace($class);
}
spl_autoload_register('___autoLoad');

/**
 * Load file chứa class Core (sử dụng Namespace)
 *
 * @param string $class Tên Class
 */
function ___loadCoreClassWithNamespace($class)
{
    // Cấu trúc Vhmis\xxx1\xxx2\xxx3
    // Filepath Core PATH \ xxx1\xxx2\xxx3.php
    $class = explode('\\', $class);

    if ($class[0] == 'Vhmis') {
        $count = count($class);
        $path = '';

        for ($i = 1; $i < $count - 1; $i++) {
            $path .= D_SPEC . $class[$i];
        }

        ___loadFile($class[$count - 1] . '.php', VHMIS_CORE2_PATH . $path);
    }
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
    if ($once == true)
        include_once $path . D_SPEC . $filename;
    else
        include $path . D_SPEC . $filename;
}

/**
 * Hàm kiểm tra tên app
 *
 * @param string $app Tên ứng dụng dạng url (thường rút gọn, ko viết hoa, sử dụng ở url
 *        hoặc đặc tên biến, index mảng)
 * @return boolean string nếu ko có apps, nếu có trả về tên App
 */
function ___checkApp($app)
{
    $config = ___loadConfig('Applications', false);

    if (!in_array($app, $config['apps']['list']['url'])) {
        return false;
    }

    return $config['apps']['list']['name'][$app];
}

/**
 * Remove Invisible Characters, hàm từ CI
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @param string
 * @param bool
 * @return string
 */
function ___removeInvisibleCharacters($str, $url_encoded = TRUE)
{
    $non_displayables = array();

    // every control character except newline (dec 10),
    // carriage return (dec 13) and horizontal tab (dec 09)
    if ($url_encoded) {
        $non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14,
                                               // 15
        $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11,
                                                                  // 12, 14-31,
                                                                  // 127

    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}

/**
 * Hàm bỏ ký tự \ cho dữ liệu mảng một hay nhiều chiều
 *
 * @param array $values mảng cần thực hiện việc loại bỏ
 * @return array mảng sau khi được loại bỏ
 */
function ___stripSlashes($values)
{
    if (is_array($values)) {
        foreach ($values as $key => $value) {
            $values[$key] = ___stripSlashes($value);
        }
    } else {
        $values = stripslashes($values);
    }

    return $values;
}