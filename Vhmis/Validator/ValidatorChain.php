<?php

namespace Vhmis\Validator;

/**
 * Collection of validator
 */
class ValidatorChain
{

    /**
     *
     * @var ValidatorAbstract[]
     */
    protected $validators;

    /**
     * 
     * @param string $name
     * @param array $options
     * 
     * @return ValidatorChain
     */
    public function add($name, $options)
    {
        if (!isset($this->validators[$name])) {
            $class = '\\Vhmis\\Validator\\' . $name;
            $this->validators[$name] = new $class();
            $this->validators[$name]->setOptions($options);
        }

        return $this;
    }
}
