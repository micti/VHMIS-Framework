<?php

namespace Vhmis\Db\MySQL;

class Result implements \Iterator
{
    /**
     *
     * @var \PDOStatement
     */
    protected $resource;

    /**
     * Giá trị cuối
     *
     * @var type
     */
    protected $lastValue;

    /**
     * Kết quả hiện tại của statement
     *
     * @var type
     */
    protected $currentData;

    /**
     *
     * @var bool
     */
    protected $hasCurrent = false;

    /**
     * Vị trí hiện tại
     *
     * @var int
     */
    protected $position = -1;

    /**
     *
     * @var mixed
     */
    protected $rowCount = null;

    public function __construct($resource, $lastValue)
    {
        $this->resource = $resource;
        $this->lastValue = $lastValue;
        $this->resource->setFetchMode(\PDO::FETCH_ASSOC);
    }

    /**
     * Lấy kết quả hiện tại
     *
     * @return array
     */
    public function current()
    {
        if($this->hasCurrent) {
            return $this->currentData;
        }

        $this->currentData = $this->resource->fetch();
        return $this->currentData;
    }

    /**
     * Đến kết quả tiếp theo
     *
     * @return array
     */
    public function next()
    {
        $this->currentData = $this->resource->fetch();
        $this->hasCurrent = true;
        $this->position++;
        return $this->currentData;
    }

    /**
     * Lấy giá trị id cuối cùng
     *
     * @return type
     */
    public function getLastValue()
    {
        return $this->lastValue;
    }

    /**
     * Kiểm tra hợp lệ
     *
     * @return bool
     */
    public function valid()
    {
        return ($this->currentData !== false);
    }

    public function rewind()
    {
        throw new \Exception('Not implement');
    }

    /**
     * Lấy vị trị hiện tại
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Số dòng (bảng ghi dữ liệu) bị ảnh hưởng
     */
    public function count()
    {
        if (is_int($this->rowCount)) {
            return $this->rowCount;
        }

        $this->rowCount = (int) $this->resource->rowCount();

        return $this->rowCount;
    }

    public function setFetchMode($mode, $name = null, $arg = null)
    {
        if(\PDO::FETCH_CLASS == $mode) {
            $this->resource->setFetchMode($mode, $name, $arg);
        } else {
            $this->resource->setFetchMode(\PDO::FETCH_ASSOC);
        }
    }
}
