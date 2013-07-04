<?php

namespace Vhmis\Validator;

class Validator
{
    const NOTVALUE = 'notvalue';
    const NULLVALUE = 'nullvalue';
    const EMPTYVALUE = 'emptyvalue';

    /**
     * Các message báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOTVALUE   => 'Không có giá trị',
        self::NULLVALUE  => 'Giá trị null',
        self::EMPTYVALUE => 'Giá trị rỗng'
    );

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
     * Giá trị chuẩn của các trường
     *
     * @var array
     */
    protected $standardValue = array();

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
     * Danh sách các trường bỏ qua không kiểm tra
     * Khi chúng nằm trong danh sách allow null hoặc empty và giá trị của chúng là null và empty
     *
     * @var array
     */
    protected $skip = array();

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
     * Trường bị lỗi
     *
     * @var string
     */
    protected $messageField = '';

    /**
     * Thông báo lỗi
     *
     * @var string
     */
    protected $message;

    /**
     * Mã lỗi
     *
     * @var string
     */
    protected $messageCode;

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

        if ($allowEmpty) {
            $this->addAllowEmpty($name);
        }

        if ($allowNull) {
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

    public function addValidator($name, $validator, $params = null)
    {
        $this->checkValidator[] = array($name, $validator, $params);

        return $this;
    }

    public function addPostValidator($name, $validator, $params = null)
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                $this->checkValidator[] = array('_POST_' . $n, $validator, $params);
            }
        } else {
            $this->checkValidator[] = array('_POST_' . $name, $validator, $params);
        }

        return $this;
    }

    public function addGetValidator($name, $validator, $params = null)
    {
        $this->checkValidator[] = array('_GET_' . $name, $validator, $params);

        return $this;
    }

    /**
     * Kiểm tra
     *
     * @return boolean
     */
    public function isValid()
    {
        // Kiểm tra xem field có giá trị ko, thực chất là post với get
        foreach ($this->name as $name) {
            /* Kiểm tra tồn tại */
            if (!array_key_exists($name, $this->value)) {
                $this->setMessage($name, $this->messages[self::NOTVALUE], self::NOTVALUE);
                return false;
            }

            /* Kiểm tra null */
            if ($this->value[$name] === null) {
                if (in_array($name, $this->allowNull)) {
                    $this->skip[] = $name;
                    continue;
                } else {
                    $this->setMessage($name, $this->messages[self::NULLVALUE], self::NULLVALUE);
                    return false;
                }
            }

            /* Kiểm tra rỗng */
            if ($this->value[$name] === '') {
                if (in_array($name, $this->allowEmpty)) {
                    $this->skip[] = $name;
                    continue;
                } else {
                    $this->setMessage($name, $this->messages[self::EMPTYVALUE], self::EMPTYVALUE);
                    return false;
                }
            }
        }

        /* Kiểm tra */
        foreach ($this->checkValidator as $validatorInfo) {
            $name = $validatorInfo[0];
            $params = $validatorInfo[2];
            $validator = $validatorInfo[1];

            // Bỏ qua kiểm tra
            if (in_array($name, $this->skip)) {
                //Không thực hiện kiểm tra
                continue;
            }

            if (array_key_exists($name, $this->standardValue)) {
                $value = $this->standardValue[$name];
            } else {
                $value = $this->value[$name];
            }

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
                $this->setMessage($name, $this->validator[$validator]->getMessage(), $this->validator[$validator]->getMessageCode());
                return false;
            }

            $this->standardValue[$name] = $this->validator[$validator]->getStandardValue();
        }

        return true;
    }

    /**
     * Lấy giá trị ban đầu của 1 trường
     *
     * @param string $name
     * @return mixed
     */
    public function getRawValue($name)
    {
        return $this->value[$name];
    }

    /**
     * Lấy giá trị chuẩn của 1 trường
     *
     * @param string $name
     * @return mixed
     */
    public function getStandardValue($name)
    {
        return $this->standardValue[$name];
    }

    public function setMessage($field, $message, $code)
    {
        $this->message = $message;
        $this->messageCode = $code;
        $this->messageField = $field;
    }

    public function getMessage($type = '')
    {
        if ($type === 'field') {
            return $this->messageField;
        } else if ($type === 'message') {
            return $this->message;
        } else if ($type === 'code') {
            return $this->messageCode;
        } else {
            return array(
                'field'   => $this->messageField,
                'message' => $this->message,
                'code'    => $this->messageCode
            );
        }
    }

    /**
     * Reset lại thông tin validator
     * 
     * @return \Vhmis\Validator\Validator
     */
    public function reset()
    {
        $this->allowEmpty = array();
        $this->allowNull = array();
        $this->value = array();
        $this->name = array();
        $this->checkValidator = array();
        $this->message = $this->messageCode = $this->messageField = '';

        return $this;
    }
}
