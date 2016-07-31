<?php

namespace Vhmis\Db\MySQL;

abstract class Entity
{
    const STATUS_NEW = 1;
    const STATUS_CHANGE = 3;
    const STATUS_DELETE = 4;
    const STATUS_SAVED = 2;

    protected $status = 1;
    
    protected $fieldNameMap = [];

    protected $oldValue = [];

    protected $currentValue = [];

    protected $hasDeleted = false;
    
    protected $idKey = 'id';
    
    protected $modelName;
    
    protected $tableName;
    
    /**
     *
     * @var Db
     */
    protected $db;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->setDataFromArray($data);
        }
    }
    
    /**
     * 
     * @param \Vhmis\Db\MySQL\Db $db
     */
    public function setDb(Db $db)
    {
        $this->db = $db;
        
        return $this;
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
        $this->status = self::STATUS_DELETE;
        $id = $this->{$this->idKey};
        $this->{$this->idKey} = null;
        
        if ($bool === false) {
            $this->status = self::STATUS_SAVED;
            $this->{$this->idKey} = $id;
        }
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
        
        $this->status = self::STATUS_SAVED;

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
    
    public function save()
    {
        if ($this->status === self::STATUS_NEW) {
            return $this->insert();
        }
        
        if ($this->isChanged()) { //$this->status === self::STATUS_CHANGE
            return $this->update();
        }
    }
    
    public function insert()
    {
        $queryInfo = $this->dataForQuery();
        
        //$table = $this->db->getModel($this->modelName)->getTableName();
        $query = $this->db->getQuery()->createInsertStatementQuery($this->tableName, $queryInfo['fields'], $queryInfo['params']);
        
        try {
            $res = $this->db->executeUpdate($query, $queryInfo['data']);
            $id = $this->db->getLastInsertId();
            $this->{$this->idKey} = $id;
            $this->setNewValue();
            
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
    
    public function update()
    {
        $queryInfo = $this->dataForQuery();
        $queryInfo['data'][':' . $this->idKey] = $this->{$this->idKey};
        $query = $this->db->getQuery()->createUpdateStatementQuery(
            $this->tableName, $queryInfo['fields'],
            [$this->idKey],
            $queryInfo['params']
        );
        
        try {
            $res = $this->db->executeUpdate($query, $queryInfo['data']);
            $this->setNewValue();
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function delete()
    {
        try {
            $this->db->executeUpdate('delete from ' . $this->tableName . ' where ' . $this->idKey . ' = :' . $this->idKey, [':' . $this->idKey => $this->{$this->idKey}]);
            $this->setDeleted(true);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    protected function dataForQuery()
    {
        $fields = $params = $data = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== null &&  $fieldSQL != $this->idKey) {
                $fields[] = '`' . $fieldSQL . '`';
                $params[] = ':' . $fieldClass;
                $data[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        return ['fields' => $fields, 'params' => $params, 'data' => $data];
    }
}
