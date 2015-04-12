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
 * Factory class to create form or its element like field, fieldset.
 */
class Factory
{

    /**
     * Create form.
     *
     * @param array $config
     *
     * @return \Vhmis\Form\Form
     */
    public static function createForm($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Form';
        }

        $form = new $config['class']();

        self::createFormDetail($form, $config);

        return $form;
    }

    /**
     * Create fieldset.
     *
     * @param array $config
     *
     * @return \Vhmis\Form\config
     */
    public static function createFieldSet($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\FieldSet';
        }

        $fieldset = new $config['class']();

        self::createFieldSetDetail($fieldset, $config);

        return $fieldset;
    }

    /**
     * Create field.
     *
     * @param array $config
     *
     * @return Field
     */
    public static function createField($config)
    {
        if (!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Field';
        }

        $field = new $config['class']();

        self::createFieldDetail($field, $config);

        return $field;
    }

    /**
     * Create form detail.
     *
     * @param Form $form Fieldset or Form
     * @param array $config Config
     */
    public static function createFormDetail($form, $config)
    {
        self::createFieldSetDetail($form, $config);

        if (isset($config['validators'])) {
            self::createFormValidators($form, $config['validators']);
        }
    }

    /**
     * Create fieldset detail
     *
     * @param Fieldset|Form $fieldset
     * @param array $config
     */
    public static function createFieldSetDetail($fieldset, $config)
    {
        $fieldset->setName($config['name']);

        if (isset($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $element = self::createField($field);
                $fieldset->addField($element);
            }
        }

        if (isset($config['fieldsets'])) {
            foreach ($config['fieldsets'] as $field) {
                $element = self::createFieldSet($field);
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
    public static function createFieldDetail($field, $config)
    {
        $field->setName($config['name']);

        if (isset($config['validators'])) {
            foreach ($config['validators'] as $validator => $options) {
                $field->addValidator($validator, $options);
            }
        }

        if (isset($config['allow'])) {
            if (in_array('null', $config['allow'])) {
                $field->allowNull();
            }

            if (in_array('empty', $config['allow'])) {
                $field->allowEmpty();
            }
        }
    }

    /**
     * Create form validators.
     *
     * @param Form $form
     * @param array $config
     */
    public static function createFormValidators($form, $config)
    {
        foreach ($config as $field => $validators) {
            foreach ($validators as $config) {
                $form->addValidator($field, $config['validator'], $config['options']);
            }
        }
    }
}
