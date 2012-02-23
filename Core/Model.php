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
}