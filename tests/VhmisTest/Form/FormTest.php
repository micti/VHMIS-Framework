<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Form;

use Vhmis\Form\Form;
use Vhmis\Form\Field;
use Vhmis\Validator\ValidatorChain;

class FormTest extends \PHPUnit_Framework_TestCase
{

    public function testSetAndGetValidatorChain()
    {
        $form = new Form();
        $validator = new ValidatorChain();
        $form->setValidatorChain($validator);
        $this->assertSame($validator, $form->getValidatorChain());
        $form2 = new Form();
        $this->assertInstanceOf('\Vhmis\Validator\ValidatorChain', $form2->getValidatorChain());
        $this->assertNotSame($validator, $form2->getValidatorChain());
    }

    public function testValidation()
    {
        $field1 = new Field();
        $field2 = new Field();
        $field3 = new Field();
        $field4 = new Field();
        $field5 = new Field();
        $form = new Form();
        $form->addField($field1->setName('a'));
        $form->addField($field2->setName('b'));
        $form->addField($field3->setName('c'));
        $form->addField($field4->setName('d'));
        $form->addField($field5->setName('e'));
        $data = [
            'a' => null,
            'b' => '',
            'c' => '5',
            'd' => 1.5,
            'e' => []
        ];
        $form->fill($data);
        $this->assertFalse($form->isValid());
        $this->assertEquals('a', $form->getNotValidField());
        $form->addValidator('a', 'Int');
        $form->addValidator('b', 'Int');
        $form->addValidator('c', 'Int');
        $form->addValidator('d', 'Float');
        $form->addValidator('e', 'Int');
        $this->assertFalse($form->isValid());
        $this->assertEquals('a', $form->getNotValidField());
        $field1->allowNull();
        $this->assertFalse($form->isValid());
        $this->assertEquals('b', $form->getNotValidField());
        $field2->allowEmpty();
        $this->assertFalse($form->isValid());
        $this->assertEquals('e', $form->getNotValidField());
        $field5->allowEmpty();
        $this->assertTrue($form->isValid());
        $this->assertEquals(null, $form->getNotValidField());
        $this->assertEquals(null, $form->getNotValidMessage());
        $this->assertEquals(null, $form->getNotValidCode());

        $standards = $form->getStandardValues();
        $values = [
            'a' => null,
            'b' => '',
            'c' => 5,
            'd' => 1.5,
            'e' => []
        ];
    }
}
