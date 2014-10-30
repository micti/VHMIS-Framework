<?php

namespace Vhmis\Form;

class Form
{
    protected $fields;
    
    protected $fieldSets;
    
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
}
