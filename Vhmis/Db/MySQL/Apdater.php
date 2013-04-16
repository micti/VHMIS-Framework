<?php

namespace Vhmis\Db\MySQL;

class Apdater
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
    }

    /**
     * Kết nối CSDL
     *
     * @return \Vhmis\Db\MySQL\Apdater
     */
    public function connect()
    {
        $this->resource = new \PDO($this->dns, $this->user, $this->pass);

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
     * @return \Vhmis\Db\MySQL\Apdater
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
        return $this->resource->exec($this->resource->quote($sql));
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
}
