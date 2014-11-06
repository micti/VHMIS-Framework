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
    protected $validates;
    protected $filter;

    public function __construct()
    {
        $this->validates = new ValidatorChain();
    }

    public function addField($name, $field)
    {
        $this->fields[$name] = $field;

        return $this;
    }

    public function addFieldSet($name, $fieldSet)
    {
        $this->fieldSets[$name] = $fieldSet;

        return $this;
    }

    public function fill($values)
    {
        foreach ($this->fields as $key => $field) {
            if (array_key_exists($key, $values)) {
                $field->setValue($values[$key]);
            }
        }

        return $this;
    }
}
