<?php

namespace Vhmis\Validator;

class NotNull extends NotSameAbstract
{

    /**
     * Error code : Missing option
     */
    const E_MISSING_OPTION = 'validator_notnull_error_missing_option';

    /**
     * Error code : Null
     */
    const E_NULL = 'validator_notnull_error_null';

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
    protected $sameCode = self::E_NULL;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_MISSING_OPTION => 'Missing some options for validation.',
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
        $this->options['comparedValue'] = null;

        return parent::isValid($value);
    }
}
