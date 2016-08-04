<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Db\MySQL;

/**
 * Abstract entity class
 */
abstract class Entity
{

    /**
     * State of entity
     */
    const STATE_NEW = 1;
    const STATE_INDB = 2;
    const STATE_DELETED = 3;

    /**
     * Id type
     */
    const IDTYPE_DBAUTO = 1;
    const IDTYPE_MANUAL = 0;

    /**
     * Current value in db
     *
     * @var array
     */
    protected $value = [];

    /**
     * Previous value in db, use to rollback
     *
     * @var array
     */
    protected $previousValue = [];

    /**
     * Current state of entity
     *
     * @var int
     */
    protected $state;

    /**
     * Mapped array between table column name and entity property name
     * [
     *     'id' => 'id',
     *     'last_name => 'lastName'
     * ]
     *
     * @var array
     */
    protected $fieldNameMap = [];

    /**
     * Info array about table column info (type, lenght)
     * [
     *     'id' => [
     *          'lenght' => 2,
     *          'type' => 'int'
     *     ]
     * ]
     *
     * @var array
     */
    protected $fieldInfo = [];

    /**
     * Name of id column
     *
     * @var string
     */
    protected $idName = 'id';

    /**
     * Value type of id column
     * 0: Manual insert
     * 1: Auto generate
     *
     * @var int
     */
    protected $idType = Entity::IDTYPE_DBAUTO;

    /**
     * Name of table in database
     *
     * @var string
     */
    protected $tableName;

    /**
     * Db
     *
     * @var Db
     */
    protected $db;
    protected $hasDeleted = false;

    public function __construct($data = null, $state = self::STATE_NEW)
    {
        if (is_array($data)) {
            $this->setDataFromArray($data);
        }

        $this->setState($state);
    }

    /**
     * Set state of entity
     * NEW, INDB, DELETED
     *
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get state of entity
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Db
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
            if ($this->$fieldClass !== $this->value[$fieldSQL]) {
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
            $this->$fieldClass = $this->value[$fieldSQL] = null;

            if (array_key_exists($fieldSQL, $data)) {
                $this->$fieldClass = $this->value[$fieldSQL] = $data[$fieldSQL];
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
        $this->previousValue = $this->value;

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            $this->value[$fieldSQL] = $this->$fieldClass;
        }

        return $this;
    }

    /**
     * Set previous value to current value
     *
     * @return \Vhmis\Db\MySQL\Entity
     */
    public function rollback()
    {
        $this->value = $this->previousValue;

        return $this;
    }

    /**
     * Get data as array
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            $data[$fieldClass] = $this->value[$fieldSQL];
        }

        return $data;
    }

    /**
     * Set id value
     *
     * @param mixed $id
     *
     * @return \Vhmis\Db\MySQL\Entity
     */
    public function setIdValue($id)
    {
        $this->{$this->fieldNameMap[$this->idName]} = $id;

        return $this;
    }

    public function getIdValue()
    {
        return $this->{$this->fieldNameMap[$this->idName]};
    }

    /**
     * Save to DB (insert or update)
     *
     * @return boolean
     */
    public function save()
    {
        switch ($this->state) {
            case 1:
            case 3:
                return $this->insert();
            case 2:
                return $this->update();
            default:
                return false;
        }
    }

    /**
     * Insert to DB
     *
     * @return boolean
     */
    public function insert()
    {
        $queryInfo = $this->dataForInsertQuery();

        // Nothing to update
        if ($queryInfo['fields'] === []) {
            return false;
        }

        //$table = $this->db->getModel($this->modelName)->getTableName();
        $query = $this->db->getQuery()->createInsertStatementQuery($this->tableName, $queryInfo['fields'], $queryInfo['params']);

        try {
            $res = $this->db->executeUpdate($query, $queryInfo['data']);
            $id = $this->db->getLastInsertId();
            $this->setIdValue($id);
            $this->state = self::STATE_INDB;
            $this->setNewValue();

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Update to databse
     *
     * @return boolean
     */
    public function update()
    {
        $queryInfo = $this->dataForUpdateQuery();

        // Nothing to update
        if ($queryInfo['fields'] === []) {
            return false;
        }

        $query = $this->db->getQuery()->createUpdateStatementQuery($this->tableName, $queryInfo['fields'], $queryInfo['idField'], $queryInfo['params'], $queryInfo['idParam']);

        try {
            $res = $this->db->executeUpdate($query, $queryInfo['data']);
            $this->setNewValue();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete from database
     *
     * @return boolean
     */
    public function delete()
    {
        if ($this->getIdValue() === null) {
            return false;
        }

        try {
            $this->db->executeUpdate('delete from ' . $this->tableName . ' where ' . $this->idName . ' = :' . $this->idName, [':' . $this->idName => $this->getIdValue()]);
            $this->setState(self::STATE_DELETED);
            $this->setIdValue(null);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Prepare data (fields, params, value for params)
     *
     * Return array includes
     * + 'fields' : Fields for insert or update (array)
     * + 'params' : Params for fields (array)
     * + 'idField' : Id field for update and delete (array)
     * + 'idParam' : Param for id field (array)
     * + 'data' : Data for id param (array)
     *
     * @return array
     */
    protected function dataForInsertQuery()
    {
        $fields = $params = $data = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== null) {
                $fields[] = '`' . $fieldSQL . '`';
                $params[] = ':' . $fieldClass;
                $data[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        return [
            'fields' => $fields,
            'params' => $params,
            'data' => $data
        ];
    }

    protected function dataForUpdateQuery()
    {
        $fields = $params = $data = [];

        foreach ($this->fieldNameMap as $fieldSQL => $fieldClass) {
            if ($this->$fieldClass !== null && $this->$fieldClass !== $this->value[$fieldSQL]) {
                $fields[] = '`' . $fieldSQL . '`';
                $params[] = ':' . $fieldClass;
                $data[':' . $fieldClass] = $this->$fieldClass;
            }
        }

        $idParam = ':' . $this->fieldNameMap[$this->idName];
        $data[$idParam] = $this->getIdValue();

        return [
            'fields' => $fields,
            'params' => $params,
            'data' => $data,
            'idField' => [$this->idName],
            'idParam' => [$idParam]
        ];
    }
}
