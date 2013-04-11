<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Config
 * @since Vhmis v2.0
 */
namespace Vhmis\Config;

use \ArrayObject;

/**
 * Class dùng để lưu và đọc các config trong toàn bộ quá trình thực thi
 *
 * @category Vhmis
 * @package Vhmis_Config
 * @subpackage Configure
 */
class Configure extends ArrayObject
{

    /**
     * Đối tượng Configure
     *
     * @var Configure
     */
    private static $_configure = null;

    /**
     * Lấy đối tượng Configure
     *
     * @return Configure
     */
    public static function getInstance()
    {
        if (self::$_configure === null) {
            self::$_configure = new Configure();
        }
        
        return self::$_configure;
    }

    /**
     * Lấy giá trị của 1 config
     *
     * Sử dụng tham số thứ 2 để thiết lập giá trị mặc định nếu config đó chưa
     * tồn tại, nếu không sử dụng thì giá trị trả về sẽ là null
     *
     * @param string $index
     * @param mixed $default Giá trị trả về mặc định nếu config chưa tồn tại
     * @return mixed
     */
    public static function get($index, $default = null)
    {
        $instance = self::getInstance();
        
        if (!$instance->offsetExists($index)) {
            return $default;
        }
        
        return $instance->offsetGet($index);
    }

    /**
     * Gán giá trị của 1 config
     *
     * @param string $index
     * @param mixed $value
     */
    public static function set($index, $value)
    {
        $instance = self::getInstance();
        
        $instance->offsetSet($index, $value);
    }

    /**
     * Thêm giá trị vào cho một config
     *
     * Nếu config đó chưa tồn tại thì sẽ khởi tạo, nếu giá trị config đã tồn tại
     * thì sẽ thêm vào theo hàm array_merge_recursive
     *
     * @param string $index
     * @param mixed $value
     */
    public static function add($index, $value)
    {
        $instance = self::getInstance();
        
        if (!$instance->offsetExists($index)) {
            $instance->offsetSet($index, $value);
        } else {
            $instance->offsetSet($index, array_merge_recursive($instance->offsetGet($index), $value));
        }
    }

    /**
     * Kiểm tra xem 1 config đã được khai báo chưa
     *
     * @param string $index
     * @return boolean
     */
    public static function isRegistered($index)
    {
        if (self::$_configure === null) {
            return false;
        }
        
        return self::$_configure->offsetExists($index);
    }
}
