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
        $this->assertInstanceOf('\Vhmis\Validator\ValidatorChain', $form->getValidatorChain());
    }

    public function testValidation()
    {
        $field1 = new Field();
        $field2 = new Field();
        $field3 = new Field();
        $form = new Form();
        $form->addField($field1->setName('a'));
        $form->addField($field2->setName('b'));
        $form->addField($field3->setName('c'));
        $data = [
            'a' => 'A',
            'b' => 'B'
        ];
        $form->fill($data);
        $form->addValidator('a', 'NotNull');
        $form->addValidator('b', 'NotNull');
        $form->addValidator('c', 'NotNull');

        $data = [
            'a' => 'A',
            'b' => 'B',
            'c' => ''
        ];
        $form->fill($data);

        $form->addValidator('a', 'NotEmpty');
        $form->addValidator('b', 'NotEmpty');
        $form->addValidator('c', 'NotEmpty');
        $this->assertFalse($form->isValid());
    }
}
