<?php

namespace Vhmis\Validator;

class Smaller extends ComapareAbstract
{

    /**
     * Error code : Empty
     */
    const E_EQUAL_OR_GREATER = 'validator_notempty_error_equal_or_greater';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_EQUAL_OR_GREATER;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_EQUAL_OR_GREATER => 'The given value is equal or greater than compared value.'
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
        return $this->value < $this->options['compare'];
    }
}
