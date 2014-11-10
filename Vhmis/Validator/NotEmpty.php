<?php

namespace Vhmis\Validator;

class NotEmpty extends NotSameAbstract
{

    /**
     * Error code : Missing option
     */
    const E_MISSING_OPTION = 'validator_notempty_error_missing_option';

    /**
     * Error code : Empty
     */
    const E_EMPTY = 'validator_notempty_error_empty';

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
    protected $sameCode = self::E_EMPTY;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_MISSING_OPTION => 'Missing some options for validation.',
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
        $this->options['comparedValue'] = '';

        return parent::isValid($value);
    }
}
