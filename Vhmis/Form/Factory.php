<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Form;

class Factory
{

    /**
     * Create form.
     *
     * @param array $config
     *
     * @return \Vhmis\Form\Form
     */
    public function createForm($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Form';
        }

        $form = new $config['class']();

        $this->createFormDetail($form, $config);
        $this->createValidators($form, $config);

        return $form;
    }

    /**
     * Create fieldset.
     *
     * @param array $config
     *
     * @return \Vhmis\Form\config
     */
    public function createFieldSet($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\FieldSet';
        }

        $fieldset = new $config['class']();

        $this->createFieldSetDetail($fieldset, $config);

        return $fieldset;
    }

    /**
     * Create field.
     *
     * @param array $config
     *
     * @return Field
     */
    public function createField($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Field';
        }

        $field = new $config['class']();

        $this->createFieldDetail($field, $config);

        return $field;
    }

    /**
     * Create form detail.
     *
     * @param Form $form Fieldset or Form
     * @param array $config Config
     */
    public function createFormDetail($form, $config)
    {
        $this->createFieldSetDetail($form, $config);
    }

    /**
     * Create fieldset detail
     *
     * @param Fieldset|Form $fieldset
     * @param array $config
     */
    public function createFieldSetDetail($fieldset, $config)
    {
        $fieldset->setName($config['name']);

        if (isset($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $element = $this->createField($field);
                $fieldset->addField($element);
            }
        }

        if (isset($config['fieldsets'])) {
            foreach ($config['fieldsets'] as $field) {
                $element = $this->createFieldSet($field);
                $fieldset->addFieldSet($element);
            }
        }
    }

    /**
     * Create field detail.
     *
     * @param Field $field Field
     * @param array $config Config
     */
    public function createFieldDetail($field, $config)
    {
        $field->setName($config['name']);
    }

    /**
     * Create validators.
     *
     * @param Form $form
     * @param array $config
     */
    public function createValidators($form, $config)
    {
        foreach ($config['validators'] as $field => $validators) {
            foreach ($validators as $config) {
                $form->addValidator($field, $config['validator'], $config['options']);
            }
        }
    }
}
