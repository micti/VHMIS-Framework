<?php

namespace Vhmis\Db\MySQL;

abstract class Entity
{
    protected $fieldNameMap = [];

    protected $oldValue = [];

    protected $currentValue = [];

    protected $hasDeleted = false;

    public function __construct($data = null)
    {
        if (is_array($data)) {
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
        $field = $param = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            // && $this->$fieldClass != $this->currentValue[$fieldSQL]
            if ($this->$fieldClass !== null && $fieldSQL != 'id') {
                $field[] = '`' . $fieldSQL . '`' . ' = :' . $fieldClass;
                $param[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        $sql = implode(', ', $field);

        return ['sql' => $sql, 'param' => $param];
    }

    public function insertSQL()
    {
        $field = $value = $param = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== null) {
                $field[] = '`' . $fieldSQL . '`';
                $value[] = ':' . $fieldClass;
                $param[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        $sql = '(' . implode(', ', $field) . ') values (' . implode(', ', $value) . ')';

        return ['sql' => $sql, 'param' => $param];
    }

    public function setDataFromArray($data)
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            $this->$fieldClass = $this->currentValue[$fieldSQL] = null;

            if (array_key_exists($fieldSQL, $data)) {
                $this->$fieldClass = $this->currentValue[$fieldSQL] = $data[$fieldSQL];
            }
        }

        return $this;
    }

    public function fillData($data)
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if (array_key_exists($fieldSQL, $data)) {
                $this->$fieldClass = $data[$fieldSQL];
            }
        }

        return $this;
    }

    public function fillEmptyData($onlyNullField = true)
    {
        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass === null) {
                $this->$fieldClass = '';
                continue;
            }

            if (!$onlyNullField) {
                $this->$fieldClass = '';
            }
        }
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

    /**
     * Lấy dữ liệu dạng array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            $data[$fieldClass] = $this->currentValue[$fieldSQL];
        }

        return $data;
    }
}
