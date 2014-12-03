<?php

namespace Vhmis\Form;

class FieldSet
{
    /**
     *
     * @var Field[] 
     */
    protected $fields = [];
    
    /**
     *
     * @var FieldSet[]
     */
    protected $fieldSets = [];
    
    public function addField($field)
    {
        $this->fields[$field->getName()] = $field;
    }
    
    public function addFieldSet($fieldSet)
    {
        $this->fieldSets[$name] = $fieldSet;
    }
    
    public function getAllFields()
    {
        $fields = [];
        foreach($this->fields as $name => $field) {
            $fields[$name] = $field;
        }
        
        foreach($this->fieldSets as $name => $set) {
            $fieldsOfSet = $set->getAllFields();
            $field += $fieldsOfSet;
        }
        
        return $fields;
    }
}
