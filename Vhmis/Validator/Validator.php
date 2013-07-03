<?php

namespace Vhmis\Validator;

class Validator
{
    /**
     * Danh sách các trường của GET
     *
     * @var array
     */
    protected $getName = array();

    /**
     * Giá trị các trường của GET
     *
     * @var array
     */
    protected $getValue = array();

    /**
     * Danh sách các trường của POST
     *
     * @var array
     */
    protected $postName = array();

    /**
     * Giá trị các trường của POST
     *
     * @var array
     */
    protected $postValue = array();

    /**
     * Giá trị các trường của POST được phép empty
     *
     * @var array
     */
    protected $postAllowEmpty = array();

    /**
     * Danh sách các đối tượng validator
     *
     * @var \Vhmis\Validator\ValidatorAbstract[]
     */
    protected $validator = array();

    /**
     * Danh sách các trường POST cần kiểm tra
     *
     * @param array $name Mảng danh sách tên
     * @param array $value Mảng danh sách giá trị
     * @return \Vhmis\Validator\Validator
     */
    public function fromPost($name, $value)
    {
        $this->postName = $name;
        $this->postValue = $value;

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
        $this->getName = $name;
        $this->getValue = $value;

        return $this;
    }

    public function addPostAllowEmpty($name)
    {
        $this->postAllowEmpty = $name;
    }

    public function addPostValidator($name, $validator, $params = null)
    {
        $this->postValidator[$name][] = array($validator, $params);

        return $this;
    }

    /**
     * Kiểm tra
     *
     * @return boolean
     */
    public function isValid()
    {
        /* 1. Kiểm tra POST */
        foreach ($this->postName as $name) {

            /* Kiểm tra tồn tại */
            if (!isset($this->postValue[$name])) {
                return false;
            }

            /* Kiểm tra rỗng */
            if ($this->postValue[$name] === '') {
                if (in_array($name, $this->postAllowEmpty)) {
                    continue;
                } else {
                    return false;
                }
            }

            $value = $this->postValue[$name];

            /* Kiểm tra */
            if(isset($this->postValidator[$name])) {
                foreach($this->postValidator[$name] as $validatorInfo) {
                    $params = $validatorInfo[1];
                    $validator = $validatorInfo[0];

                    // Khởi tạo validator nếu chưa có
                    if(!isset($this->validator[$validator])) {
                        $class = '\Vhmis\Validator\\' . $validator;
                        $this->validator[$validator] = new $class();
                    }

                    // Thiết lập options
                    if($params !== null) {
                        $this->validator[$validator]->setOptions($params);
                    }

                    if(!$this->validator[$validator]($value)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
