<?php

namespace Vhmis\Validator;

class Arr extends ValidatorAbstract
{
    const NOTARRAY = 'notarray';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOTARRAY => 'Giá trị không phải là mảng'
    );
    
    public function setOptions($options)
    {
        $this->options = $options;
        
        return $this;
    }

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
