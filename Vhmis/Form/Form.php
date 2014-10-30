<?php

namespace Vhmis\Form;

class Form
{
    /**
     *
     * @var Field[]
     */
    protected $fields;
    
    protected $fieldSets;
    
    protected $validate;
    
    protected $filter;
    
    public function __construct()
    {
        
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
        foreach($values as $key => $value) {
            $this->fields[$key]->setValue($value);
        }
        
        return $this;
    }
}
