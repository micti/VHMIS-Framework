<?php

namespace Vhmis\Validator;

class NotEmpty extends ValidatorAbstract
{
    const E_EMPTY = 'validator_notempty_error_empty';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_EMPTY => 'The given value is empty.'
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

        if ($value === '') {
            $this->setNotValidInfo(self::E_EMPTY, $this->messages[self::E_EMPTY]);
            return false;
        }

        return true;
    }
}
