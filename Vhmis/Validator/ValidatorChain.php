<?php

namespace Vhmis\Validator;

use Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * Validator chain.
 */
class ValidatorChain
{

    /**
     * Validator objects.
     *
     * @var ValidatorAbstract[]
     */
    protected $validators;

    /**
     * List of class of validators.
     *
     * @var array
     */
    protected $validtorList = [
        'Int' => '\Vhmis\Validator\Int',
        'Float' => '\Vhmis\Validator\Float',
        'Greater' => '\Vhmis\Validator\Greater',
        'Smaller' => '\Vhmis\Validator\Smaller',
        'Same' => '\Vhmis\Validator\Same',
        'NotEmpty' => '\Vhmis\Validator\NotEmpty',
        'NotNull' => '\Vhmis\Validator\NotNull',
        'NotSame' => '\Vhmis\Validator\NotSame',
        'Array' => '\Vhmis\Validator\Arr',
        'DateTime' => '\Vhmis\Validator\DateTime',
        'Range' => '\Vhmis\Validator\Range',
        'Upload' => '\Vhmis\Validator\Upload'
    ];

    /**
     * Field and its value, validators.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Not valid field.
     *
     * @var string
     */
    protected $notValidField;

    /**
     * Not valid message.
     *
     * @var string
     */
    protected $notValidMessage;

    /**
     * Not valid code.
     *
     * @var string
     */
    protected $notValidCode;

    /**
     * Standard values of fields after valid validation.
     */
    protected $standardValues = [];

    /**
     * Add validator.
     *
     * @param string $name
     * @param array $options
     *
     * @return ValidatorChain
     */
    public function add($field, $validator, $options = [])
    {
        if (!isset($this->validtorList[$validator])) {
            throw new InvalidArgumentException('Invalid validator.');
        }

        $this->addField($field);

        $this->fields[$field]['validator'][$validator] = $options;

        return $this;
    }

    /**
     * Add value for a field.
     *
     * @param string $field
     * @param mixed $value
     *
     * @return ValidatorChain
     */
    public function addValue($field, $value)
    {
        $this->addField($field);

        $this->fields[$field]['value'] = $value;
        $this->standardValues[$field] = $value;

        return $this;
    }

    /**
     * Fill values for all fields.
     *
     * @param array $values
     *
     * @return ValidatorChain
     */
    public function fill($values)
    {
        foreach ($this->fields as $key => $unusedValue) {
            if (array_key_exists($key, $values)) {
                $this->fields[$key]['value'] = $values[$key];
                $this->standardValues[$key] = $values[$key];
            }
        }

        return $this;
    }

    /**
     * Get all fields.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get standard values of fields.
     *
     * @return array
     */
    public function getStandardValues()
    {
        return $this->standardValues;
    }

    /**
     * Reset.
     *
     * @return ValidatorChain
     */
    public function reset()
    {
        $this->fields = [];

        return $this;
    }

    /**
     * Validate for all fields.
     *
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->fields as $key => $field) {
            foreach ($field['validator'] as $validator => $options) {
                $validatorObject = $this->getValidator($validator);
                $validatorObject->reset();
                $validatorObject->setOptions($options);

                if (!$validatorObject->isValid($this->standardValues[$key])) {
                    $this->notValidField = $key;
                    $this->notValidMessage = $validatorObject->getMessage();
                    $this->notValidCode = $validatorObject->getMessageCode();
                    return false;
                }

                $this->standardValues[$key] = $validatorObject->getStandardValue();
            }
        }

        return true;
    }

    /**
     * Get not valid field.
     *
     * @return string
     */
    public function getNotValidField()
    {
        return $this->notValidField;
    }

    /**
     * Get not valid message.
     *
     * @return string
     */
    public function getNotValidMessage()
    {
        return $this->notValidMessage;
    }

    /**
     * Get not valid code.
     *
     * @return string
     */
    public function getNotValidCode()
    {
        return $this->notValidCode;
    }

    /**
     * Get validator.
     *
     * @param string $name
     *
     * @return ValidatorAbstract
     */
    protected function getValidator($name)
    {
        if (!isset($this->validators[$name])) {
            $this->validators[$name] = new $this->validtorList[$name];
        }

        return $this->validators[$name];
    }

    /**
     * Add field if it isn't set.
     *
     * @param string $field
     *
     * @return ValidatorChain
     */
    protected function addField($field)
    {
        if (!isset($this->fields[$field])) {
            $this->fields[$field]['value'] = null;
            $this->fields[$field]['validator'] = [];
            $this->standardValues[$field] = null;
        }

        return $this;
    }
}
