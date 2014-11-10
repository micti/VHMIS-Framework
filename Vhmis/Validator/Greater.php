<?php

namespace Vhmis\Validator;

class Greater extends ComapareAbstract
{
    /**
     * Error code : Empty
     */
    const E_EQUAL_OR_SMALLER = 'validator_notempty_error_equal_or_smaller';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_EQUAL_OR_SMALLER;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_EQUAL_OR_SMALLER => 'The given value is equal or smaller than compared value.'
    );

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['compare'];

    /**
     * Compare method.
     *
     * @return boolean
     */
    protected function compare()
    {
        return $this->value > $this->options['compare'];
    }
}
