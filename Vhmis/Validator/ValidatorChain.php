<?php

namespace Vhmis\Validator;

/**
 * Collection of validator
 *
 * @author Micti
 */
class ValidatorChain
{

    /**
     *
     * @var ValidatorAbstract[]
     */
    protected $validators;

    public function add($name, $options)
    {
        if (!isset($this->validators[$name])) {
            $class = '\\Vhmis\\Validator\\' . $name;
            $this->validators[$name] = new $class();
            $this->validators[$name]->setOptions($options);
        }
    }
}
