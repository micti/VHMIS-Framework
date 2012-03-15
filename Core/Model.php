<?php

abstract class Vhmis_Model extends Zend_Db_Table_Abstract
{
    /**
     * Tự động thiết lập tên bảng
     */
    protected function _setupTableName()
    {
        if(!$this->_name)
        {
            $this->_name = strtolower(str_replace('Vhmis_Model_', '', get_class($this)));
        }
        parent::_setupTableName();
    }

    /**
     * Chuyển một đối tượng row sang dạng mảng
     *
     * @return mảng dữ liệu của đối tượng
     */
    public function toArray($object)
    {
        return $object->toArray();
    }

    /**
     * Chuyển một đối tượng row sang entity dạng mảng (cùng chức năng với toArray)
     *
     * @return mảng dữ liệu của đối tượng
     */
    public function toEntity($object)
    {
        return $this->toArray($object);
    }

    /**
     * Hàm lấy dữ liệu theo primary key
     *
     * @param mixed $keys Giá trị của các primary key
     */
    public function find($keys)
    {
        // Đối với bảng chỉ có 1 primary key, có thể đưa vào chuỗi chứa nhiều primary key, cách nhau bằng dấu phẩy
        if(is_string($keys))
        {
            $keys = explode(',', $keys);
        }

        $rowset = parent::find($keys);

        $total = count($rowset);

        if($total > 1) return $rowset;

        if($total == 1) return $rowset[0];

        return null;
    }

    /**
     * Lấy dữ liệu theo primary key id
     * Hàm này là ánh xạ của hàm find()
     *
     * @param mixed $ids Giá trị của các id
     */
    public function getById($ids)
    {
        return $this->find($ids);
    }
}