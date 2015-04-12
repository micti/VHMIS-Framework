<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Form;

/**
 * Form
 */
class Form extends FieldSet
{

    /**
     * Validator chain
     *
     * @var FormValidatorChain
     */
    protected $validatorChain;

    /**
     * Set validator chain.
     *
     * @param FormValidatorChain $validatorChain
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
     * @return FormValidatorChain
     */
    public function getValidatorChain()
    {
        if ($this->validatorChain === null) {
            $this->validatorChain = new FormValidatorChain;
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
        $this->getValidatorChain()->addValidator($field, $validator, $options);

        return $this;
    }

    /**
     * Validate form.
     *
     * @return bool
     */
    public function isValid()
    {
        // Add field info to validator chain
        $fields = $this->getAllFields();
        foreach ($fields as $field) {
            foreach ($field->getValidators() as $validator => $options) {
                $this->getValidatorChain()->addValidator($field->getName(), $validator, $options);
            }

            $this->getValidatorChain()->addValue($field->getName(), $field->getValue());
            $this->getValidatorChain()->addAllowEmpty($field->getName(), $field->isAllowedEmpty());
            $this->getValidatorChain()->addAllowNull($field->getName(), $field->isAllowedNull());
        }

        // Check
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

    /**
     * Get field that is not valid.
     *
     * @return string
     */
    public function getNotValidField()
    {
        return $this->getValidatorChain()->getNotValidField();
    }

    /**
     * Get not valid code.
     *
     * @return string
     */
    public function getNotValidCode()
    {
        return $this->getValidatorChain()->getNotValidCode();
    }

    /**
     * Get not valid message.
     *
     * @return string
     */
    public function getNotValidMessage()
    {
        return $this->getValidatorChain()->getNotValidMessage();
    }
}
