<?php

namespace Vhmis\Form;

class FieldSet
{
    protected $data;
    
    public function addField($name)
    {
        $this->data[$name] = 1;
    }
}
