<?php

namespace Vhmis\Db\Mongo;

class Adapter implements \Vhmis\Db\AdapterInterface
{
    /**
     * Đối tượng MongoClient
     *
     * @var \MongoClient
     */
    protected $resource;

    /**
     * Đối tượng MongoDb
     *
     * @var \MongoDb
     */
    protected $db;

    /**
     * Khởi tạo
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->dns = 'mongodb://' . $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->dbName = $config['db'];

        if ($this->dns === null || $this->user === null || $this->pass === null) {
            echo 'DbError - Config';
            exit();
        }

        if ($config['auto']) {
            $this->connect();
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
            if ($this->user === '' && $this->pass === '') {
                $this->resource = new \MongoClient($this->dns);
            } else {
                $this->resource = new \MongoClient($this->dns, array("username" => $this->user, "password" => $this->pass));
            }
        } catch (\MongoException $e) {
            echo 'DbError';
            exit();
        }

        $this->db = $this->resource->selectDB($this->dbName);

        return $this;
    }

    /**
     * Kiểm tra đã kết nối tới CSDL chưa
     *
     * @return bool
     */
    public function isConnected()
    {
        return ($this->resource instanceof \MongoClient && $this->db instanceof \MongoDb);
    }

    /**
     * Ngắt kết nối
     *
     * @return \Vhmis\Db\Mongo\Adapter
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->resource->close();
            $this->resource = null;
            $this->db = null;
        }

        return $this;
    }

    /**
     * Lấy đối tượng của kết nối
     *
     * @return \MongoDb
     */
    public function getConnection()
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->db;
    }

    public function qoute($value)
    {
        return $value;
    }

    public function query($sql)
    {
        return false;
    }

    public function createStatement($sql = null, $parameters = null)
    {
        return false;
    }

    public function lastValue($name = null)
    {
        return false;
    }

    public function beginTransaction()
    {
        return false;
    }

    public function commit()
    {
        return false;
    }

    public function rollback()
    {
        return false;
    }
}
