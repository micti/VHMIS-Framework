<?php

namespace Vhmis\Form;

use Vhmis\Validator\ValidatorChain;
use Vhmis\Validator\NotEmpty;
use Vhmis\Validator\NotNull;

/**
 * Validator chain for form.
 */
class FormValidatorChain extends ValidatorChain
{

    /**
     * Add field, set allow null and empty are false by default.
     *
     * @param string $field
     *
     * @return FormValidatorChain
     */
    public function addField($field)
    {
        if (!isset($this->fields[$field])) {
            $this->fields[$field]['value'] = null;
            $this->fields[$field]['validator'] = [];
            $this->fields[$field]['allow_null'] = false;
            $this->fields[$field]['allow_empty'] = false;
            $this->standardValues[$field] = null;
        }

        return $this;
    }

    /**
     * Set field value can/can not be empty.
     *
     * @param string $field
     * @param boolean $allow
     *
     * @return FormValidatorChain
     */
    public function addAllowEmpty($field, $allow)
    {
        $this->addField($field);
        $this->fields[$field]['allow_empty'] = $allow;

        return $this;
    }

    /**
     * Set field value can be null.
     *
     * @param string $field
     * @param boolean $allow
     *
     * @return FormValidatorChain
     */
    public function addAllowNull($field, $allow)
    {
        $this->addField($field);
        $this->fields[$field]['allow_null'] = $allow;

        return $this;
    }

    /**
     * Add validator, skip if 'NotEmpty' or 'NotNull' validator.
     *
     * @param string $field
     * @param string $validator
     * @param array $options
     *
     * @return FormValidatorChain
     */
    public function addValidator($field, $validator, $options = array())
    {
        if ($validator === 'NotEmpty' || $validator === 'NotNull') {
            return $this;
        }

        parent::addValidator($field, $validator, $options);

        return $this;
    }

    public function isValid($skippedFields = [])
    {
        $this->clearResult();
        $this->skipFields = $skippedFields;

        foreach ($this->fields as $key => $field) {
            if (!$this->isValidFieldWithNullValue($key, $field)) {
                return false;
            }

            if (!$this->isValidFieldWithEmptyValue($key, $field)) {
                return false;
            }

            if (!$this->isValidField($key, $field['validator'])) {
                return false;
            }
        }

        return true;
    }

    protected function isValidFieldWithNullValue($key, $field)
    {
        if ($field['value'] === null) {
            if ($field['allow_null'] === false) {
                $this->setNotValidInfo($key, NotNull::E_NULL, '');
                return false;
            }

            $this->skipFields[] = $key;
        }

        return true;
    }

    protected function isValidFieldWithEmptyValue($key, $field)
    {
        if ($field['value'] === '' || $field['value'] === []) {
            if ($field['allow_empty'] === false) {
                $this->setNotValidInfo($key, NotEmpty::E_EMPTY, '');
                return false;
            }

            $this->skipFields[] = $key;
        }

        return true;
    }
}
