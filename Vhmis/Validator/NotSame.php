<?php

namespace Vhmis\Validator;

class NotSame extends ComapareAbstract
{

    /**
     * Error code : Empty
     */
    const E_SAME = 'validator_notsame_error_same';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_SAME;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_SAME => 'The given value is same with compared value.'
    );

    /**
     * Compare method.
     *
     * @return boolean
     */
    protected function compare()
    {
        return $this->value !== $this->options['comparedValue'];
    }
}
