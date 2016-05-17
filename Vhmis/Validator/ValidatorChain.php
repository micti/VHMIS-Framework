<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

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
    protected $validatorList = [
        'IntegerNumber' => '\Vhmis\Validator\IntegerNumber',
        'DbExists'      => '\Vhmis\Validator\DbExists',
        'FloatNumber'   => '\Vhmis\Validator\FloatNumber',
        'Greater'       => '\Vhmis\Validator\Greater',
        'Smaller'       => '\Vhmis\Validator\Smaller',
        'Same'          => '\Vhmis\Validator\Same',
        'NotEmpty'      => '\Vhmis\Validator\NotEmpty',
        'NotNull'       => '\Vhmis\Validator\NotNull',
        'NotSame'       => '\Vhmis\Validator\NotSame',
        'Array'         => '\Vhmis\Validator\Arr',
        'DateTime'      => '\Vhmis\Validator\DateTime',
        'Range'         => '\Vhmis\Validator\Range',
        'Upload'        => '\Vhmis\Validator\Upload',
        'FileName'      => '\Vhmis\Validator\FileName',
        'FolderName'    => '\Vhmis\Validator\FolderName'
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
     * Skipped fields when validating.
     */
    protected $skipFields = [];

    /**
     * Add field if it isn't set.
     *
     * @param string $field
     *
     * @return ValidatorChain
     */
    public function addField($field)
    {
        if (!isset($this->fields[$field])) {
            $this->fields[$field]['value'] = null;
            $this->fields[$field]['validator'] = [];
            $this->standardValues[$field] = null;
        }

        return $this;
    }

    /**
     * Add validator.
     *
     * @param string $field
     * @param string $validator
     * @param array  $options
     *
     * @return ValidatorChain
     */
    public function addValidator($field, $validator, $options = [])
    {
        if (!isset($this->validatorList[$validator])) {
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
     * @param mixed  $value
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
     * Remove a field.
     *
     * @param string $field
     *
     * @return ValidatorChain
     */
    public function removeField($field)
    {
        unset($this->fields[$field]);
        unset($this->standardValues[$field]);

        return $this;
    }

    /**
     * Fill values for all exist fields.
     *
     * @param array $values
     *
     * @return ValidatorChain
     */
    public function fill($values)
    {
        foreach ($values as $key => $value) {
            if (array_key_exists($key, $this->fields)) {
                $this->fields[$key]['value'] = $value;
                $this->standardValues[$key] = $value;
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
        $this->standardValues = [];
        $this->notValidCode = $this->notValidField = $this->notValidMessage = null;

        return $this;
    }

    /**
     * Validate for all fields.
     *
     * @param array $skippedFields
     *
     * @return boolean
     */
    public function isValid($skippedFields = [])
    {
        $this->clearResult();
        $this->skipFields = $skippedFields;

        foreach ($this->fields as $key => $field) {
            if (!$this->isValidField($key, $field['validator'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get not valid field.
     *
     * @return string|null
     */
    public function getNotValidField()
    {
        return $this->notValidField;
    }

    /**
     * Get not valid message.
     *
     * @return string|null
     */
    public function getNotValidMessage()
    {
        return $this->notValidMessage;
    }

    /**
     * Get not valid code.
     *
     * @return string|null
     */
    public function getNotValidCode()
    {
        return $this->notValidCode;
    }

    protected function isValidField($field, $validators)
    {
        if (in_array($field, $this->skipFields)) {
            return true;
        }

        foreach ($validators as $validator => $options) {
            $validatorObject = $this->getValidator($validator);
            $validatorObject->reset();
            $validatorObject->setOptions($options);
            if (!$validatorObject->isValid($this->standardValues[$field])) {
                $this->setNotValidInfo($field, $validatorObject->getMessageCode(), $validatorObject->getMessage());

                return false;
            }

            $this->standardValues[$field] = $validatorObject->getStandardValue();
        }

        return true;
    }

    /**
     * Clear last result before validate again.
     */
    protected function clearResult()
    {
        $this->notValidCode = $this->notValidField = $this->notValidMessage = null;

        foreach ($this->fields as $key => $field) {
            $this->standardValues[$key] = $field['value'];
        }
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
            $this->validators[$name] = new $this->validatorList[$name];
        }

        return $this->validators[$name];
    }

    /**
     * Set not valid info.
     *
     * @param string $field
     * @param string $code
     * @param string $message
     */
    protected function setNotValidInfo($field, $code, $message)
    {
        $this->notValidCode = $code;
        $this->notValidField = $field;
        $this->notValidMessage = $message;
    }
}
