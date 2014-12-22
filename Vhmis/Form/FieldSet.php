<?php

namespace Vhmis\Form;

class FieldSet extends Field
{
    /**
     * Fields.
     *
     * @var Field[]
     */
    protected $fields = [];

    /**
     * Field sets.
     *
     * @var FieldSet[]
     */
    protected $fieldSets = [];

    /**
     * Form factory.
     *
     * @var Factory
     */
    protected $factory;

    /**
     * Set form factory.
     *
     * @param Factory $factory
     *
     * @return FieldSet
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * Get form factory.
     *
     * @return Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->setFactory(new Factory());
        }

        return $this->factory;
    }

    /**
     * Add field.
     *
     * @param Field|array $field
     *
     * @return Fieldset
     */
    public function addField($field)
    {
        if (is_array($field)) {
            $field = $this->getFactory()->createField($field);
        }

        if (!($field instanceof Field)) {
            return false;
        }

        $name = $field->getName();
        if ($name === null || $name === '') {
            return false;
        }

        $this->fields[$name] = $field;

        return $this;
    }

    /**
     * Add another fieldset.
     *
     * @param FieldSet $fieldSet
     *
     * @return Fieldset
     */
    public function addFieldSet($fieldSet)
    {
        $this->fieldSets[$fieldSet->getName()] = $fieldSet;

        return $this;
    }

    /**
     * Get all fields.
     *
     * @return Field[]
     */
    public function getAllFields()
    {
        $fields = [];

        foreach ($this->fields as $name => $field) {
            $fields[$name] = $field;
        }

        foreach ($this->fieldSets as $name => $set) {
            $fieldsOfSet = $set->getAllFields();
            $field += $fieldsOfSet;
        }

        return $fields;
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
        $fields = $this->getAllFields();

        foreach ($fields as $key => $field) {
            if (array_key_exists($key, $values)) {
                $field->setValue($values[$key]);
            }
        }

        return $this;
    }
}
