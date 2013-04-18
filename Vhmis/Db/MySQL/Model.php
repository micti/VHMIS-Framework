<?php

namespace Vhmis\Db\MySQL;

class Model
{
    /**
     * Tên class Model (bắt đầu bằng \)
     *
     * @var string
     */
    protected $class;

    /**
     * Tên class Entity
     *
     * @var string
     */
    protected $entityClass;

    /**
     * Tên bảng ứng với model
     *
     * @var string
     */
    protected $table;

    /**
     * Adapter
     *
     * @var \Vhmis\Db\MySQL\Adapter
     */
    protected $adapter = null;

    /**
     * Tên trường primary key
     *
     * @var string
     */
    protected $idKey = 'id';

    /**
     * Khởi tạo
     *
     * @param \Vhmis\Db\MySQL\Adapter $adapter
     */
    public function __construct(Adapter $adapter = null)
    {
        if($adapter instanceof Adapter) {
            $this->setAdapter($adapter);
        }
    }

    /**
     * Thiết lập adapter
     *
     * @param \Vhmis\Db\MySQL\Adapter $adapter
     * @return \Vhmis\Db\MySQL\Model
     */
    public function setAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this->init();
    }

    /**
     * Khởi tạo model sau khi thiết lập adapter thành công
     *
     * @return \Vhmis\Db\MySQL\Model
     * @throws \Exception
     */
    public function init()
    {
        if (!$this->adapter instanceof Adapter) {
            throw new \Exception('Need an Adapter');
        }

        $this->class = '\\' . get_class($this);
        $this->entityClass = $this->class . 'Entity';

        $class = explode('\\', $this->class);
        if ($this->table == '') {
            $this->table = $this->camelCaseToUnderscore($class[count($class) - 1]);
        }

        return $this;
    }

    /**
     * Tìm tất cả dữ liệu
     *
     * - Nếu bảng chưa có giá trị thì sẽ trả về mảng rỗng
     * - Nếu bảng đã có dữ liệu thì sẽ trả về mảng chứa các đối tượng Entity tương ứng với Model
     * 
     * @return array
     */
    public function findAll()
    {
        $sql = 'select * from `' . $this->table . '`';

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setSql($sql)->execute();

        $data = array();

        while ($row = $result->next()) {
            $data[] = $this->fillRowToEntityClass($row);
        }

        return $data;
    }

    public function findById($id)
    {
        $sql = 'select * from `' . $this->table . '` where ' . $this->idKey . ' = ?';

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setParameters(array(1 => $id))->setSql($sql)->execute();

        if ($row = $result->current()) {
            return $this->fillRowToEntityClass($row);
        } else {
            return null;
        }
    }

    protected function fillRowToEntityClass($row)
    {
        $entity = new $this->entityClass();

        foreach ($row as $key => $value) {
            $method = 'set' . $this->underscoreToCamelCase($key);
            $entity->$method($value);
        }

        return $entity;
    }

    protected function camelCaseToUnderscore($str)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
    }

    protected function underscoreToCamelCase($str, $ucfirst = false)
    {
        $parts = explode('_', $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }
}
