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
     * Khởi tạo
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->dns = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];

        if ($config['auto']) {
            $this->connect();
        }

        if($this->dns === null || $this->user === null || $this->pass === null) {
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
        try {
            $this->resource = new \PDO($this->dns, $this->user, $this->pass);
        } catch (\PDOException $e) {
            echo 'DbError';
            exit();
        }

        $this->resource->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->resource->exec('SET NAMES \'UTF8\'');

        return $this;
    }

    /**
     * Kiểm tra đã kết nối tới CSDL chưa
     *
     * @return bool
     */
    public function isConnected()
    {
        return ($this->resource instanceof \PDO);
    }

    /**
     * Ngắt kết nối
     *
     * @return \Vhmis\Db\MySQL\Adapter
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->resource = null;
        }

        return $this;
    }

    /**
     * Lấy đối tượng PDO của kết nối
     *
     * @return \PDO
     */
    public function getConnection()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->resource;
    }

    /**
     * Thực hiện một query
     *
     * @param string $sql
     * @return int Số bảng ghi bị ảnh hưởng
     */
    public function query($sql)
    {
        $result = $this->resource->exec($sql);
        return $result;
    }

    /**
     * Tạo một statement mới
     *
     * @param string $sql
     * @param array $parameters
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

        if (!$this->isConnected()) {
            $this->connect();
        }

        $statement->setAdapter($this);

        return $statement;
    }

    /**
     * Lấy giá trị mới (id) tạo ra
     *
     * @param string $name
     * @return int|boolean
     */
    public function lastValue($name = null)
    {
        try {
            return $this->resource->lastInsertId($name);
        } catch (\Exception $e) {
            //
        }

        return false;
    }

    public function beginTransaction()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->resource->beginTransaction();
        $this->inTransaction = true;

        return $this;
    }

    public function commit()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        $this->resource->commit();

        return $this;
    }

    public function rollback()
    {
        if (!$this->isConnected()) {
            throw new \Exception('Must be connected before you can rollback');
        }

        if (!$this->inTransaction) {
            throw new \Exception('Must call beginTransaction() before you can rollback');
        }

        $this->resource->rollBack();
        $this->inTransaction = false;

        return $this;
    }
}
