<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Form;

use Vhmis\Form\Factory;

class FactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testFactory()
    {
        $factory = new Factory();

        $config = [
            'name' => 'form1',
            'fields' => [
                [
                    'name' => 'field1'
                ],
                [
                    'name' => 'field2'
                ],
                [
                    'name' => 'field3'
                ],
                [
                    'name' => 'field4'
                ]
            ],
            'fieldsets' => [
                [
                    'name' => 'fieldset1',
                    'fields' => [
                        [
                            'name' => 'field5'
                        ],
                        [
                            'name' => 'field6'
                        ],
                        [
                            'name' => 'field7'
                        ],
                        [
                            'name' => 'field8'
                        ]
                    ]
                ],
                [
                    'name' => 'fieldset2',
                    'fields' => [
                        [
                            'name' => 'field9'
                        ]
                    ],
                    'fieldsets' => [
                        [
                            'name' => 'fieldsets3',
                            'fields' => [
                                [
                                    'name' => 'field10'
                                ],
                                [
                                    'name' => 'field11'
                                ],
                                [
                                    'name' => 'field12'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'validators' => [
                'field1' => [
                    [
                        'validator' => 'IntegerNumber',
                        'options' => []
                    ]
                ]
            ]
        ];

        $form = $factory->createForm($config);

        $this->assertInstanceOf('\Vhmis\Form\Form', $form);
        $allFields = [
            'field1',
            'field2',
            'field3',
            'field4',
            'field5',
            'field6',
            'field7',
            'field8',
            'field9',
            'field10',
            'field11',
            'field12',
        ];

        $fieldsOfForm = $form->getAllFields();

        foreach ($allFields as $field) {
            $this->assertInstanceOf('\Vhmis\Form\Field', $fieldsOfForm[$field]);
        }
    }
}
