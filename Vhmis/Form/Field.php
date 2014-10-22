<?php

namespace Vhmis\Form;

class Field
{

    /**
     * Name of field.
     * 
     * @var string
     */
    protected $name;

    /**
     * Type of field.
     * 
     * @var string
     */
    protected $type;

    /**
     * Set name.
     * 
     * @param string $name
     * 
     * @return Field
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type.
     * 
     * @param string $type
     * 
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
