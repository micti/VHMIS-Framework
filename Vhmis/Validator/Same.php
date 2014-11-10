<?php

namespace Vhmis\Validator;

class Same extends ComapareAbstract
{

    /**
     * Error code : Empty
     */
    const E_NOT_SAME = 'validator_same_error_not_same';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_NOT_SAME;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_SAME => 'The given value is not same with compared value.'
    );

    /**
     * Compare method.
     *
     * @return boolean
     */
    protected function compare()
    {
        return $this->value === $this->options['comparedValue'];
    }
}
