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
        foreach ($values as $key => $value) {
            if (isset($this->fields[$key])) {
                $this->fields[$key]->setValue($value);
            }
        }

        return $this;
    }

}
