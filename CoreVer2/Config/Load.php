<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link       http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright  Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @package    Vhmis_Config
 * @since      Vhmis v2.0
 */
namespace Vhmis\Config;

/**
 * Class dùng để load các config từ file
 *
 * Hiện tại chỉ sử dụng với các các file config php dạng array
 *
 * @category Vhmis
 * @package Vhmis_Config
 * @subpackage Load
 */
class Load
{

    /**
     * Load config chứa trong mảng ở file php
     *
     * @param string $filename
     *            Đường dẫn file chứa thông tin config
     * @return array
     */
    public static function filePhp($filename)
    {
        if (is_file($filename) && is_readable($filename)) {
            $config = include $filename;
        } else {
            $config = null;
        }
        
        return $config;
    }
}
