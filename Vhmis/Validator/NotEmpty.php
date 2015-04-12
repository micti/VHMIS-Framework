<?php

namespace Vhmis\Validator;

/**
 * Validator for not empty value.
 */
class NotEmpty extends ComapareAbstract
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
     * Compare method.
     *
     * @return boolean
     */
    protected function compare()
    {
        return (!empty($this->value));
    }
}
