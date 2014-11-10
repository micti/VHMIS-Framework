<?php

namespace Vhmis\Validator;

class NotNull extends ComapareAbstract
{

    /**
     * Error code : Null
     */
    const E_NULL = 'validator_notnull_error_null';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_NULL;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_NULL => 'The given value is null.'
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
        return $this->value !== null;
    }
}
