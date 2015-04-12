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
 * Field of form.
 */
class Field
{

    use FormElementTrait;

    /**
     * Type of field.
     *
     * @var string
     */
    protected $type;

    /**
     * Value of field.
     *
     * @var string
     */
    protected $value;

    /**
     * Validators of field.
     *
     * @var array
     */
    protected $validators = [];

    /**
     * Field value can be null.
     *
     * @var boolean
     */
    protected $allowNull = false;

    /**
     * Field value can be empty.
     *
     * @var boolean
     */
    protected $allowEmpty = false;

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

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return Field
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add validator.
     *
     * @param string $validator
     * @param array $options
     *
     * @return Field
     */
    public function addValidator($validator, $options = null)
    {
        $this->validators[$validator] = $options;

        return $this;
    }

    /**
     * Get validators.
     *
     * @return array
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Set field value can be empty.
     */
    public function allowEmpty()
    {
        $this->allowEmpty = true;
    }

    /**
     * Set field value can be null.
     */
    public function allowNull()
    {
        $this->allowNull = true;
    }

    /**
     * Is field value can be empty.
     *
     * @return boolean
     */
    public function isAllowedEmpty()
    {
        return $this->allowEmpty;
    }

    /**
     * Is field value can be null.
     *
     * @return boolean
     */
    public function isAllowedNull()
    {
        return $this->allowNull;
    }
}
