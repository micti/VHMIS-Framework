<?php

namespace Vhmis\Validator;

class Arr extends ValidatorAbstract
{

    /**
     * Error code : Not array.
     */
    const E_NOT_ARRAY = 'validator_array_error_not_array';

    /**
     * Error messages.
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_ARRAY => 'The given value is not an array.'
    );

    /**
     * Validate.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!is_array($value)) {
            $this->setNotValidInfo(self::E_NOT_ARRAY, $this->messages[self::E_NOT_ARRAY]);
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
