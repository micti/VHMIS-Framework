<?php

namespace Vhmis\Form;


/**
 * Create form from config array
 * 'type' => 'form',
 * 'name' => 'name',
 * 'attr' => [],
 * 'fieldsets' => [
 *   ''
 * ]
 *
 *
 *
 */
class Factory
{
    /**
     *
     * @var Form[]
     */
    protected $factory;

    /**
     * Create form.
     *
     * @param array $config
     *
     * @return \Vhmis\Form\Form
     */
    public function createForm($config)
    {
        if(!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Form';
        }

        $form = new $config['class']();
        $this->factory[$config['name']] = $form;

        $this->createFormDetail($form, $config);

        return $form;
    }

    public function createFieldSet()
    {

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
        if(!isset($config['class'])) {
            $config['class'] = '\\Vhmis\\Form\\Field';
        }

        $field = new $config['class']();

        $this->createFieldDetail($field, $config);

        return $field;
    }

    /**
     * Create form detail.
     *
     * Other attributes, fieldsets, fields....
     *
     * @param array $config
     */
    protected function createFormDetail($form, $config)
    {
        foreach ($config['fields'] as $field) {
            $element = $this->createField($field);
            $form->addField($element->getName(), $element);
        }

        foreach ($config['fieldsets'] as $field) {
            $element = $this->createField($field);
            $form->addFieldSet($element->getName(), $element);
        }
    }

    /**
     * Create field detail.
     *
     * @param Field $field
     * @param array $config
     */
    protected function createFieldDetail($field, $config)
    {
        $field->setName($config['name']);
    }
}
