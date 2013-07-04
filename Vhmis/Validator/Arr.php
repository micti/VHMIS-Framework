<?php

namespace Vhmis\Validator;

class Arr extends ValidatorAbstract
{
    const NOTARRAY = 'notarray';

    protected $messages = array(
        self::NOTARRAY => 'Giá trị không phải là mảng'
    );

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
            $this->setMessage(self::NOTARRAY);
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
