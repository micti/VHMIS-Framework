<?php

namespace Vhmis\Db\MySQL;

abstract class Entity
{
    protected $fieldNameMap = array();
    protected $oldValue = array();
    protected $currentValue = array();
    protected $hasDeleted = false;

    public function __construct($data = null)
    {
        if(is_array($data))
        {
            $this->setDataFromArray($data);
        }
    }

    public function getFieldNameMap()
    {
        return $this->fieldNameMap;
    }

    public function isChanged()
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== $this->currentValue[$fieldSQL]) {
                return true;
            }
        }

        return false;
    }

    public function setDeleted($bool)
    {
        $this->hasDeleted = $bool;
    }

    public function isDeleted()
    {
        return $this->hasDeleted;
    }

    public function updateSQL()
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            // && $this->$fieldClass != $this->currentValue[$fieldSQL]
            if ($this->$fieldClass !== null && $fieldSQL != 'id') {
                $field[] = $fieldSQL . ' = :' . $fieldClass;
                $param[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        $sql = implode(', ', $field);

        return array('sql' => $sql, 'param' => $param);
    }

    public function insertSQL()
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== null && $fieldSQL != 'id') {
                $field[] = $fieldSQL;
                $value[] = ':' . $fieldClass;
                $param[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        $sql = '(' . implode(', ', $field) . ') values (' . implode(', ', $value) . ')';

        return array('sql' => $sql, 'param' => $param);
    }

    public function setDataFromArray($data)
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if (array_key_exists($fieldSQL, $data)) {
                $this->$fieldClass = $this->currentValue[$fieldSQL] = $data[$fieldSQL];
            } else {
                $this->$fieldClass = $this->currentValue[$fieldSQL] = null;
            }
        }

        return $this;
    }

    public function setNewValue()
    {
        // Trước khi thiết lập giá trị mới (đã lưu vào CSDL) thì lưu giá trị cũ
        $this->oldValue = $this->currentValue;

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            $this->currentValue[$fieldSQL] = $this->$fieldClass;
        }

        return $this;
    }

    public function rollback()
    {
        $this->currentValue = $this->oldValue;

        return $this;
    }
}
