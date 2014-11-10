<?php

namespace Vhmis\Validator;

class NotEmpty extends NotSameAbstract
{

    /**
     * Error code : Empty
     */
    const E_EMPTY = 'validator_notempty_error_empty';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_EMPTY;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_EMPTY => 'The given value is empty.'
    );

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = [];

    /**
     * Validate
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->options['comparedValue'] = '';

        return parent::isValid($value);
    }
}
