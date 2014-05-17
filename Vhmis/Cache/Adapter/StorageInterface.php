<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Cache
 * @since Vhmis v2.0
 */
namespace Vhmis\Cache\Adapter;

/**
 * Interface chính, gồm các method lưu và lấy dữ liệu của 1 một adapter Cache
 *
 * @category Vhmis
 * @package Vhmis_Cache
 */
interface StorageInterface
{

    /**
     * Lấy giá trị ứng
     *
     * @param string $id
     */
    public function get($id);

    /**
     * Thiết lập giá trị
     *
     * @param string $id    Tên giá trị
     * @param mixed  $value
     */
    public function set($id, $value);

    /**
     * Xóa một giá trị
     *
     * @param type $id
     */
    public function remove($id);

    /**
     * Xóa tất cả
     */
    public function removeAll();
}
