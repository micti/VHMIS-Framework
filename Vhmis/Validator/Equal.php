<?php

namespace Vhmis\Validator;

class Equal extends ValidatorAbstract
{
    const NOTEQUAL = 'notequal';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOTEQUAL => 'Giá trị không bằng giá trị được so sánh'
    );

    /**
     * Giá trị được đem ra so sánh
     *
     * @var mixed
     */
    protected $comparedValue;

    public function setOptions($options)
    {
        if(isset($options['compare'])) {
            $this->comparedValue = $options['compare'];
        }

        return $this;
    }

    /**
     * Kiểm tra xem có bằng với giá trị cần so sánh không
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = null;

        if ($value !== $this->comparedValue) {
            $this->setMessage(self::NOTEQUAL);
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
