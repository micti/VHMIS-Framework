<?php

namespace Vhmis\Library\Marc\Structure;

class Field
{

    protected $code;
    protected $value = '';
    protected $id1 = ' ';
    protected $id2 = ' ';

    /**
     * Sub field list
     * 
     * @var SubField[]
     */
    protected $subfields = [];

    public function __construct($code, $id1 = '', $id2 = '')
    {
        $this->code = $code;
        $this->id1 = $id1;
        $this->id2 = $id1;
    }

    /**
     * 
     * @param type $value
     * 
     * @return Field
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * 
     * @return string
     */
    public function get1stIndicator()
    {
        return $this->id1;
    }

    /**
     * 
     * @return string
     */
    public function get2ndIndicator()
    {
        return $this->id2;
    }

    /**
     * Add subfield
     * 
     * @param SubField $subfield
     * 
     * @return Field
     */
    public function addSubField($subfield)
    {
        $this->subfields[] = $subfield;

        return $this;
    }

    /**
     * Remove subfield
     * 
     * @param Subfield $subfield
     * 
     * @return boolean
     */
    public function removeSubField($subfield)
    {
        foreach ($this->subfields as $key => $value) {
            if ($value === $subfield) {
                unset($this->subfields[$key]);
                $this->subfields = array_values($this->subfields);
                return true;
            }
        }

        return false;
    }

    /**
     * 
     * @param string $code
     * 
     * @return SubField[]
     */
    public function getSubFieldCode($code)
    {
        $subfields = [];
        foreach ($this->subfields as $subfield) {
            if ($subfield->getCode() === $code) {
                $subfields[] = $subfield;
            }
        }

        return $subfields;
    }
    
    /**
     * 
     * @return SubField[]
     */
    public function getSubFields()
    {
        return $this->subfields;
    }
}
