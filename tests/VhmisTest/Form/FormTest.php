<?php

namespace VhmisTest\Form;

use Vhmis\Form\Form;
use Vhmis\Form\Field;

class FormTest extends \PHPUnit_Framework_TestCase
{

    public function testFillTest()
    {
        $field1 = new Field();
        $field2 = new Field();
        $field3 = new Field();
        $form = new Form();
        $form->addField('a', $field1);
        $form->addField('b', $field2);
        $form->addField('c', $field3);
        $data = [
            'a' => 'A',
            'b' => 'B'
        ];

        $form->fill($data);
        $this->assertSame('A', $field1->getValue());
        $this->assertSame('B', $field2->getValue());
        $this->assertSame(null, $field3->getValue());
    }

    public function testValidation()
    {
        $field1 = new Field();
        $field2 = new Field();
        $field3 = new Field();
        $form = new Form();
        $form->addField('a', $field1);
        $form->addField('b', $field2);
        $form->addField('c', $field3);
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
