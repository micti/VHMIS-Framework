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
 * Fieldset of form.
 */
class FieldSet
{

    use FormElementTrait;

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
     * Add field.
     *
     * @param Field|array $field
     *
     * @return Fieldset
     */
    public function addField($field)
    {
        if (is_array($field)) {
            $field = Factory::createField($field);
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
     * @param FieldSet|array $fieldSet
     *
     * @return Fieldset
     */
    public function addFieldSet($fieldSet)
    {
        if (is_array($fieldSet)) {
            $fieldSet = Factory::createFieldSet($fieldSet);
        }

        if (!($fieldSet instanceof FieldSet)) {
            return false;
        }

        $name = $fieldSet->getName();
        if ($name === null || $name === '') {
            return false;
        }

        $this->fieldSets[$name] = $fieldSet;

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
            $fields += $fieldsOfSet;
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
