<?php

namespace Vhmis\Validator;

class Arr extends ValidatorAbstract
{
    /**
     * Kiểm tra xem có phải là mảng là không
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = null;

        if (!is_array($value)) {
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
