<?php

namespace Vhmis\Validator;

class Range extends ComapareAbstract
{

    /**
     * Error code : Empty
     */
    const E_OUT_RANGE = 'validator_notempty_error_out_range';

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode = self::E_OUT_RANGE;

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = array(
        self::E_OUT_RANGE => 'The given value is out range.'
    );

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['min', 'max'];

    /**
     * Compare method.
     *
     * @return boolean
     */
    protected function compare()
    {
        return $this->value >= $this->options['min'] && $this->value <= $this->options['max'];
    }
}
