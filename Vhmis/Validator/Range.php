<?php

namespace Vhmis\Validator;

class Range extends ValidatorAbstract
{
    /**
     * Giá trị cận dưới đem ra so sánh
     *
     * @var mixed
     */
    protected $minValue;

    /**
     * Giá trị cận trên đem ra so sánh
     *
     * @var mixed
     */
    protected $maxValue;

    public function setOptions($options)
    {
        if(isset($options['max'])) {
            $this->maxValue = $options['max'];
        }

        if(isset($options['min'])) {
            $this->minValue = $options['min'];
        }

        return $this;
    }

    /**
     * Kiểm tra xem nằm trong khoảng không
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = null;

        if ($value > $this->maxValue || $value < $this->minValue) {
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
