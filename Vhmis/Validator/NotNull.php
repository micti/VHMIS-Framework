<?php

namespace Vhmis\Validator;

class NotNull extends ValidatorAbstract
{
    const E_NULL = 'validator_notnull_error_null';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_NULL => 'The given value is null.'
    );

    /**
     * Validate
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;
        $this->standardValue = $value;

        if ($value === null) {
            $this->setNotValidInfo(self::E_NULL, $this->messages[self::E_NULL]);
            return false;
        }

        return true;
    }
}
