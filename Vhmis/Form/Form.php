<?php

namespace Vhmis\Form;

use \Vhmis\Validator\ValidatorChain;

class Form
{

    /**
     * Fields
     *
     * @var Field[]
     */
    protected $fields;

    /**
     * Field sets.
     *
     * @var FieldSet[]
     */
    protected $fieldSets;

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
     * Add field.
     *
     * @param string $name
     * @param Field $field
     *
     * @return Form
     */
    public function addField($name, $field)
    {
        $this->fields[$name] = $field;

        return $this;
    }

    /**
     * Add field set.
     *
     * @param string $name
     * @param FieldSet $fieldSet
     *
     * @return Form
     */
    public function addFieldSet($name, $fieldSet)
    {
        $this->fieldSets[$name] = $fieldSet;

        return $this;
    }

    /**
     * Fill values.
     *
     * @param array $values
     *
     * @return Form
     */
    public function fill($values)
    {
        foreach ($this->fields as $key => $field) {
            if (array_key_exists($key, $values)) {
                $field->setValue($values[$key]);
            }
        }

        return $this;
    }

    /**
     * Add validator for field.
     *
     * @param string $field
     * @param string $validator
     * @param array $options
     *
     * @return \Vhmis\Form\Form
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
