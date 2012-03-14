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
}