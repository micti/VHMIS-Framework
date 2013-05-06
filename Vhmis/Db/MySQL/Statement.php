<?php

namespace Vhmis\Db\MySQL;

class Statement
{
    /**
     * Adapter
     *
     * @var \Vhmis\Db\MySQL\Adapter
     */
    protected $adapter;

    /**
     * Đối tượng PDOStatement
     *
     * @var \PDOStatement
     */
    protected $resource;

    /**
     * Câu sql của Statement
     *
     * @var string
     */
    protected $sql;

    /**
     * Parameters mặc định của Statement
     *
     * @var string
     */
    protected $parameters = array();

    /**
     * Trạng thái statement đã prepare chưa
     *
     * @var bool
     */
    protected $isPrepared = false;

    /**
     * Thiết lập adapter
     *
     * @param type $adapter
     * @return \Vhmis\Db\MySQL\Statement
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Thiết lập sql
     *
     * @param type $sql
     * @return \Vhmis\Db\MySQL\Statement
     */
    public function setSql($sql)
    {
        $this->sql = $sql;

        return $this;
    }

    /**
     * Thiết lập parameters
     *
     * @param type $adapter
     * @return \Vhmis\Db\MySQL\Statement
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Chuẩn bị
     *
     * @param type $sql
     * @return type
     * @throws \Exception
     */
    public function prepare($sql = null)
    {
        if ($this->isPrepared)
            return;

        if ($sql == null) {
            $sql = $this->sql;
        }

        try {
            $this->resource = $this->adapter->getConnection()->prepare($sql);
        } catch (\PDOException $e) {
            $this->isPrepared = false;
        }
    }

    /**
     * Thực thi
     *
     * @param type $parameters
     * @return \Vhmis\Db\MySQL\Result
     * @throws Exception
     */
    public function execute($parameters = null)
    {
        if (!$this->isPrepared) {
            $this->prepare();
        }

        if (is_array($this->parameters) && count($this->parameters) > 0) {
            foreach ($this->parameters as $key => &$value) {
                $this->resource->bindParam($key, $value);
            }
        }

        if (is_array($parameters) && count($parameters) > 0) {
            foreach ($parameters as $param) {
                $count = count($param);
                switch ($count) {
                    case 4:
                        $this->resource->bindParam($param[0], $param[1], $param[2], $param[3]);
                        break;
                    case 3:
                        $this->resource->bindParam($param[0], $param[1], $param[2]);
                        break;
                    default:
                        $this->resource->bindParam($param[0], $param[1]);
                        break;
                }
            }
        }

        try {
            $this->resource->execute();
        } catch (\PDOException $e) {
            throw $e;
        }

        $result = new Result($this->resource, $this->adapter->lastValue());

        return $result;
    }
}
