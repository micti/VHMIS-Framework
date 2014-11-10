<?php

namespace Vhmis\Validator;

class NotSame extends NotSameAbstract
{
    const E_MISSING_OPTION = 'validator_notsame_error_missing_option';
    const E_SAME = 'validator_notsame_error_same';
    
    /**
     * Error code of missing option
     *
     * @var string
     */
    protected $missingOptionsCode = self::E_MISSING_OPTION;
    
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
        self::E_MISSING_OPTION => 'Missing some options for validation.',
        self::E_SAME => 'The given value is same with compared value.'
    );
}
