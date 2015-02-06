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
     * Fatory forms cache
     *
     * @var Form[]
     */
    protected $forms;

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
        $this->factory[$config['name']] = $form;

        $this->createDetail($form, $config);

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

        $this->createDetail($fieldset, $config);

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
     * Create form or fieldset detail.
     *
     * @param Form|Fieldset $form Fieldset or Form
     * @param array $config Config
     */
    protected function createDetail($form, $config)
    {
        $form->setName($config['name']);

        if (isset($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $element = $this->createField($field);
                $form->addField($element);
            }
        }

        if (isset($config['fieldsets'])) {
            foreach ($config['fieldsets'] as $field) {
                $element = $this->createFieldSet($field);
                $form->addFieldSet($element);
            }
        }
    }

    /**
     * Create field detail.
     *
     * @param Field $field Field
     * @param array $config Config
     */
    protected function createFieldDetail($field, $config)
    {
        $field->setName($config['name']);
    }
}
