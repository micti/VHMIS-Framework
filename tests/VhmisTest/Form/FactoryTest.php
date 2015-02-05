<?php

namespace VhmisTest\Form;

use Vhmis\Form\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function atestFactory() {
        $factory = new Factory();
        
        $config = [
            'name' => 'form1',
            'fields' => [
                'field1' => [],
                'field2' => [],
                'field3' => [],
            ],
            'fieldsets' => [
                'fieldset1' => [
                    'fields' => [
                        'field5' => [],
                        'field6' => [],
                        'field7' => [],
                        'field8' => [],
                    ]
                ],
                'fieldset2' => [
                    'fields' => [
                        'field9' => [],
                    ],
                    'fieldsets' => [
                        'fieldsets3' => [
                            'fields' => [
                                'field10' => [],
                                'field11' => [],
                                'field12' => [],
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        $form = $factory->createForm($config);
        
        $this->assertInstanceOf('\Vhmis\Form\Form', $form);
    }
}
