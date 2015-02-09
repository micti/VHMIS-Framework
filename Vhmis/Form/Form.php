<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Form;

use \Vhmis\Validator\ValidatorChain;

class Form extends FieldSet
{

    /**
     * Validator chain
     *
     * @var ValidatorChain
     */
    protected $validatorChain;

    /**
     * Construct.
     */
    public function __construct()
    {
        
    }

    /**
     * Set validator chain.
     * 
     * @param ValidatorChain $validatorChain
     * 
     * @return Form
     */
    public function setValidatorChain($validatorChain)
    {
        $this->validatorChain = $validatorChain;

        return $this;
    }

    /**
     * Get validator chain.
     * 
     * @return ValidatorChain
     */
    public function getValidatorChain()
    {
        if ($this->validatorChain === null) {
            $this->validatorChain = new ValidatorChain;
        }

        return $this->validatorChain;
    }

    /**
     * Add validator for field.
     *
     * @param string $field
     * @param string $validator
     * @param array $options
     *
     * @return Form
     */
    public function addValidator($field, $validator, $options = [])
    {
        $this->getValidatorChain()->add($field, $validator, $options);

        return $this;
    }

    /**
     * Validate form.
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->fields as $key => $field) {
            $this->getValidatorChain()->addValue($key, $field->getValue());
        }

        return $this->getValidatorChain()->isValid();
    }

    /**
     * Get standard values of form field after valid validation.
     *
     * @return array
     */
    public function getStandardValues()
    {
        return $this->getValidatorChain()->getStandardValues();
    }
}
