<?php

namespace Vhmis\Validator;

abstract class NotSameAbstract extends ValidatorAbstract
{
    /**
     * Error code of missing option
     *
     * @var string
     */
    protected $missingOptionsCode;
    
    /**
     * Error code of same
     *
     * @var string
     */
    protected $sameCode;

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
        $this->standardValue = $value;
        
        if (!array_key_exists('comparedValue', $this->options)) {
            $this->setNotValidInfo($this->missingOptionsCode, $this->messages[$this->missingOptionsCode]);
            return false;
        }

        if ($value === $this->options['comparedValue']) {
            $this->setNotValidInfo($this->sameCode, $this->messages[$this->sameCode]);
            return false;
        }

        return true;
    }
}
