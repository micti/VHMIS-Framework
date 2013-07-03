<?php

namespace Vhmis\Validator;

class Validator
{
    /**
     * Danh sách các trường
     *
     * @var array
     */
    protected $name = array();

    /**
     * Giá trị các trường
     *
     * @var array
     */
    protected $value = array();

    /**
     * Giá trị các trường được phép empty
     *
     * @var array
     */
    protected $allowEmpty = array();

    /**
     * Giá trị các trường được phép null
     *
     * @var array
     */
    protected $allowNull = array();

    /**
     * Danh sách cần kiểm tra tính hợp lệ
     *
     * @var array
     */
    protected $checkValidator = array();

    /**
     * Danh sách các đối tượng validator
     *
     * @var \Vhmis\Validator\ValidatorAbstract[]
     */
    protected $validator = array();

    /**
     * Thêm một trường vào để kiểm tra
     *
     * @param string $name Tên
     * @param mixed $value Giá trị
     * @param boolean $allowEmpty Cho phép rỗng
     * @param boolean $allowNull Cho phép null
     * @return \Vhmis\Validator\Validator
     */
    public function addField($name, $value, $allowEmpty = false, $allowNull = false)
    {
        if (!in_array($name, $this->name)) {
            $this->name[] = $name;
        }

        $this->value[$name] = $value;

        if($allowEmpty) {
            $this->addAllowEmpty($name);
        }

        if($allowNull) {
            $this->addAllowNull($name);
        }

        return $this;
    }

    /**
     * Danh sách các trường POST cần kiểm tra
     *
     * @param array $name Mảng danh sách tên
     * @param array $value Mảng danh sách giá trị
     * @return \Vhmis\Validator\Validator
     */
    public function fromPost($name, $value)
    {
        foreach ($name as $n) {
            if (!in_array('_POST_' . $n, $this->name)) {
                $this->name[] = '_POST_' . $n;
            }
        }

        foreach ($value as $k => $v) {
            $this->value['_POST_' . $k] = $v;
        }

        return $this;
    }

    /**
     * Danh sách các trường GET cần kiểm tra
     *
     * @param array $name Mảng danh sách tên
     * @param array $value Mảng danh sách giá trị
     * @return \Vhmis\Validator\Validator
     */
    public function fromGet($name, $value)
    {
        foreach ($name as $n) {
            if (!in_array('_GET_' . $n, $this->name)) {
                $this->name[] = '_GET_' . $n;
            }
        }

        foreach ($value as $k => $v) {
            $this->value['_GET_' . $k] = $v;
        }

        return $this;
    }

    /**
     * Cho phép một trường nhận giá trị rỗng
     *
     * @param string $name
     * @return \Vhmis\Validator\Validator
     */
    public function addAllowEmpty($name)
    {
        if (!in_array($name, $this->allowEmpty)) {
            $this->allowEmpty[] = $name;
        }

        return $this;
    }

    /**
     * Cho phép các trường POST nhận giá trị rỗng
     *
     * @param array $name
     * @return \Vhmis\Validator\Validator
     */
    public function addPostAllowEmpty($name)
    {
        foreach ($name as $n) {
            $this->addAllowEmpty('_POST_' . $n);
        }

        return $this;
    }

    /**
     * Cho phép các trường GET nhận giá trị rỗng
     *
     * @param array $name
     * @return \Vhmis\Validator\Validator
     */
    public function addGetAllowEmpty($name)
    {
        foreach ($name as $n) {
            $this->addAllowEmpty('_GET_' . $n);
        }

        return $this;
    }

    /**
     * Cho phép một trường nhận giá trị null
     *
     * @param string $name
     * @return \Vhmis\Validator\Validator
     */
    public function addAllowNull($name)
    {
        if (!in_array($name, $this->allowNull)) {
            $this->allowNull[] = $name;
        }

        return $this;
    }

    /**
     * Cho phép các trường POST nhận giá trị null
     *
     * @param array $name
     * @return \Vhmis\Validator\Validator
     */
    public function addPostAllowNull($name)
    {
        foreach ($name as $n) {
            $this->addAllowNull('_POST_' . $n);
        }

        return $this;
    }

    /**
     * Cho phép các trường GET nhận giá trị null
     *
     * @param array $name
     * @return \Vhmis\Validator\Validator
     */
    public function addGetAllowNull($name)
    {
        foreach ($name as $n) {
            $this->addAllowNull('_GET_' . $n);
        }

        return $this;
    }

    public function addPostValidator($name, $validator, $params = null)
    {
        $this->checkValidator['_POST_' . $name][] = array($validator, $params);

        return $this;
    }

    public function addGetValidator($name, $validator, $params = null)
    {
        $this->checkValidator['_GET_' . $name][] = array($validator, $params);

        return $this;
    }

    /**
     * Kiểm tra
     *
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->name as $name) {

            /* Kiểm tra tồn tại */
            if (!array_key_exists($name, $this->value)) {
                return false;
            }

            /* Kiểm tra null */
            if ($this->value[$name] === null) {
                if (in_array($name, $this->allowNull)) {
                    continue;
                } else {
                    return false;
                }
            }

            /* Kiểm tra rỗng */
            if ($this->value[$name] === '') {
                if (in_array($name, $this->allowEmpty)) {
                    continue;
                } else {
                    return false;
                }
            }

            $value = $this->value[$name];

            /* Kiểm tra */
            if (isset($this->checkValidator[$name])) {
                foreach ($this->checkValidator[$name] as $validatorInfo) {
                    $params = $validatorInfo[1];
                    $validator = $validatorInfo[0];

                    // Khởi tạo validator nếu chưa có
                    if (!isset($this->validator[$validator])) {
                        $class = '\Vhmis\Validator\\' . $validator;
                        $this->validator[$validator] = new $class();
                    }

                    // Thiết lập options
                    if ($params !== null) {
                        $this->validator[$validator]->setOptions($params);
                    }

                    if (!$this->validator[$validator]($value)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
