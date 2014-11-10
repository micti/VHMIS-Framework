<?php

namespace Vhmis\Validator;

abstract class NotSameAbstract extends ValidatorAbstract
{

    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode;

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['comparedValue'];

    /**
     * Validate
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        $this->checkMissingOptions();

        if ($value === $this->options['comparedValue']) {
            $this->setNotValidInfo($this->sameCode, $this->messages[$this->sameCode]);
            return false;
        }

        $this->standardValue = $value;
        return true;
    }
}
