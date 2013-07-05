<?php

namespace Vhmis\Validator;

class Greater extends ValidatorAbstract
{
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
     * Kiểm tra xem có lớn hơn với giá trị cần so sánh không
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = null;

        if ($value < $this->comparedValue) {
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
