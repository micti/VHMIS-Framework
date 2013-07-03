<?php

namespace Vhmis\Validator;

class Equal extends ValidatorAbstract
{
    /**
     * Giá trị được đem ra so sáng bằng
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
     * Kiểm tra xem có bằng với giá trị cần so sáng không
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = null;

        if ($value !== $this->comparedValue) {
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
