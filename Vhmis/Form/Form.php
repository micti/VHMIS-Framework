<?php

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
        $this->validatorChain = new ValidatorChain();
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
        $this->validatorChain->add($field, $validator, $options);

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
            $this->validatorChain->addValue($key, $field->getValue());
        }

        return $this->validatorChain->isValid();
    }

    /**
     * Get standard values of form field after valid validation.
     *
     * @return array
     */
    public function getStandardValues()
    {
        return $this->validatorChain->getStandardValues();
    }
}
