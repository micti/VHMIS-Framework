<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\FormValidatorChainTest;

use Vhmis\Form\FormValidatorChain;

class FormValidatorChainTest extends \PHPUnit\Framework\TestCase
{

    public function testAddAndGetField()
    {
        $chain = new FormValidatorChain;
        $chain->addField('a');
        $fields = [
            'a' => [
                'value' => null,
                'allow_null' => false,
                'allow_empty' => false,
                'validator' => []
            ]
        ];
        $this->assertEquals($fields, $chain->getFields());
    }

    public function testAddAllowNullAndEmpty()
    {
        $chain = new FormValidatorChain;
        $chain->addField('a');
        $chain->addAllowEmpty('a', true);
        $fields = [
            'a' => [
                'value' => null,
                'allow_null' => false,
                'allow_empty' => true,
                'validator' => []
            ]
        ];
        $this->assertEquals($fields, $chain->getFields());

        $chain->addAllowNull('a', true);
        $fields = [
            'a' => [
                'value' => null,
                'allow_null' => true,
                'allow_empty' => true,
                'validator' => []
            ]
        ];
        $this->assertEquals($fields, $chain->getFields());
    }

    public function testAddValidator()
    {
        $chain = new FormValidatorChain;
        $chain->addField('a');
        $chain->addValidator('a', 'NotEmpty');
        $chain->addValidator('a', 'NotNull');
        $fields = [
            'a' => [
                'value' => null,
                'allow_null' => false,
                'allow_empty' => false,
                'validator' => []
            ]
        ];
        $this->assertEquals($fields, $chain->getFields());
        $chain->addValidator('a', 'IntegerNumber');
        $fields = [
            'a' => [
                'value' => null,
                'allow_null' => false,
                'allow_empty' => false,
                'validator' => [
                    'IntegerNumber' => []
                ]
            ]
        ];
        $this->assertEquals($fields, $chain->getFields());
    }

    public function testValidation()
    {
        $chain = new FormValidatorChain;
        $chain->addField('a');
        $chain->addField('b');
        $chain->addField('c');
        $chain->addField('d');
        $chain->addField('e');
        $data = [
            'a' => null,
            'b' => '',
            'c' => '5',
            'd' => 1.5,
            'e' => []
        ];
        $chain->fill($data);
        $this->assertFalse($chain->isValid());
        $this->assertEquals('a', $chain->getNotValidField());
        $chain->addValidator('a', 'IntegerNumber');
        $chain->addValidator('b', 'IntegerNumber');
        $chain->addValidator('c', 'IntegerNumber');
        $chain->addValidator('d', 'FloatNumber');
        $chain->addValidator('e', 'IntegerNumber');
        $this->assertFalse($chain->isValid());
        $this->assertEquals('a', $chain->getNotValidField());
        $chain->addAllowNull('a', true);
        $this->assertFalse($chain->isValid());
        $this->assertEquals('b', $chain->getNotValidField());
        $chain->addAllowEmpty('b', true);
        $this->assertFalse($chain->isValid());
        $this->assertEquals('e', $chain->getNotValidField());
        $chain->addAllowEmpty('e', true);
        $a = $chain->isValid();
        $this->assertTrue($a);
        $this->assertEquals(null, $chain->getNotValidField());
        $this->assertEquals(null, $chain->getNotValidMessage());
        $this->assertEquals(null, $chain->getNotValidCode());
    }
}
