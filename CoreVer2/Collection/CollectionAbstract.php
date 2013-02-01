<?php
namespace Vhmis\Collection;

/**
 * Lớp Abstract để sử dụng tính năng __get và __set trong quản lý tập các đối
 * tượng của một class
 */
abstract class CollectionAbstract
{

    /**
     * Mảng chứa tập hợp
     *
     * @var array
     */
    protected $_collection = array();

    /**
     * Lấy giá trị theo tên
     *
     * @param string $name
     *            Tên của đối tượng cần lấy giá trị
     * @return mixed Giá trị ứng với tên hoặc Null nếu không có
     */
    public function __get($name)
    {
        if (isset($this->_collection[$name])) {
            return $this->_collection[$name];
        }
        
        return null;
    }

    /**
     * Thiết lập giá trị
     *
     * @param type $name
     *            Tên đối tượng
     * @param type $object
     *            Đối tượng (giá trị, class ...)
     */
    public function __set($name, $object)
    {
        $this->_collection[$name] = $object;
    }

    /**
     * Kiểm tra xem một đối tượng nào đó đã được khởi tạo theo tên chưa
     *
     * @param string $name
     *            Tên của đối tượng
     * @return boolean
     */
    public function __isset($name)
    {
        if (isset($this->_collection[$name]))
            return true;
        
        return false;
    }

    /**
     * Khởi tạo một đối tượng dựa theo tên class
     *
     * @param string $class
     *            Tên class cần khởi tạo
     * @param mixed $params
     *            Thông số khi khởi tạo đối tượng
     */
    abstract public function create($class, $params = null);
}