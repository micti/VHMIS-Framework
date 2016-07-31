<?php

namespace Vhmis\Db\MySQL;

class Adapter implements \Vhmis\Db\AdapterInterface
{

    /**
     * Đối tượng PDO
     *
     * @var \PDO
     */
    protected $resource;
    
    /**
     * 
     * @var boolean
     */
    protected $isConnected;

    /**
     * Khởi tạo
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->dns = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->options = $config['options'];

        if ($config['auto']) {
            $this->connect();
        }

        if ($this->dns === null || $this->user === null || $this->pass === null) {
            echo 'DbError - Config';
            exit();
        }
    }

    /**
     * Kết nối CSDL
     *
     * @return \Vhmis\Db\MySQL\Adapter
     */
    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        try {
            $this->resource = new \PDO($this->dns, $this->user, $this->pass, $this->options);
        } catch (\PDOException $e) {
            echo 'DbError';
            exit();
        }

        $this->resource->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->resource->exec('SET NAMES \'UTF8\'');

        $this->isConnected = true;

        return true;
    }

    /**
     * Kiểm tra đã kết nối tới CSDL chưa
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * Ngắt kết nối
     *
     * @return \Vhmis\Db\MySQL\Adapter
     */
    public function disconnect()
    {
        if (!$this->isConnected) {
            return false;
        }

        $this->resource = null;
        $this->isConnected = false;

        return true;
    }

    /**
     * Lấy đối tượng PDO của kết nối
     *
     * @return \PDO
     */
    public function getConnection()
    {
        $this->connect();

        return $this->resource;
    }

    /**
     * Quote 1 giá trị
     *
     * @param mixed $value
     *
     * @return string
     */
    public function qoute($value, $type = \PDO::PARAM_STR)
    {
        return $this->getConnection()->quote($value, $type);
    }

    /**
     * Thực hiện một query
     *
     * @param string $sql
     *
     * @return int Số bảng ghi bị ảnh hưởng
     */
    public function query($sql)
    {
        $result = $this->getConnection()->exec($sql);

        return $result;
    }

    /**
     * Tạo một statement mới
     *
     * @param string $sql
     * @param array  $parameters
     *
     * @return \Vhmis\Db\MySQL\Statement
     */
    public function createStatement($sql = null, $parameters = null)
    {
        $statement = new Statement;

        if (is_string($sql)) {
            $statement->setSql($sql);
        }

        if (is_array($parameters)) {
            $statement->setParameters($parameters);
        }

        $this->connect();

        $statement->setAdapter($this);

        return $statement;
    }

    /**
     * Lấy giá trị mới (id) tạo ra
     *
     * @param string $name
     *
     * @return int|boolean
     */
    public function lastValue($name = null)
    {
        return $this->lastInsertId($name);
    }

    public function lastInsertId($name = null)
    {
        try {
            return $this->resource->lastInsertId($name);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function executeUpdate($query, array $params = [])
    {
        $this->connect();
        
        if ($params === []) {
            return $this->resource->exec($query);
        }
        
        $statement = $this->createStatement($query, $params);
        $result = $statement->execute();
        
        return $result->count();
    }

    public function beginTransaction()
    {
        $this->connect();

        $this->resource->beginTransaction();
        $this->inTransaction = true;

        return $this;
    }

    public function commit()
    {
        if (!$this->inTransaction) {
            throw new \Exception('Must call beginTransaction() before you can rollback');
        }

        $this->resource->commit();
        $this->inTransaction = false;

        return $this;
    }

    public function rollback()
    {
        if (!$this->inTransaction) {
            throw new \Exception('Must call beginTransaction() before you can rollback');
        }

        $this->resource->rollBack();
        $this->inTransaction = false;

        return $this;
    }
}
