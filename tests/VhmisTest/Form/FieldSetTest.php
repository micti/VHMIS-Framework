<?php

namespace VhmisTest\Form;

use Vhmis\Form\FieldSet;
use Vhmis\Form\Field;
use Vhmis\Form\Factory;

class FieldSetTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $fieldset = new FieldSet;
        $fac1 = $fieldset->getFactory();
        $this->assertInstanceOf('\Vhmis\Form\Factory', $fac1);

        $fac2 = new Factory;
        $fieldset->setFactory($fac2);
        $this->assertSame($fac2, $fieldset->getFactory());
    }

    public function testFillTest()
    {
        $field1 = new Field;
        $field2 = new Field;
        $field3 = new Field;
        $fieldset = new FieldSet;
        $fieldset->addField($field1->setName('a'));
        $fieldset->addField($field2->setName('b'));
        $fieldset->addField($field3->setName('c'));
        $data = [
            'a' => 'A',
            'b' => 'B'
        ];

        $fieldset->fill($data);
        $this->assertSame('A', $field1->getValue());
        $this->assertSame('B', $field2->getValue());
        $this->assertSame(null, $field3->getValue());
    }
}
