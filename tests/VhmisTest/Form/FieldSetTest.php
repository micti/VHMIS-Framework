<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

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

    public function testAddField()
    {
        $fieldset = new FieldSet;

        $fieldA = new Field;
        $fieldA->setName('a');
        $fieldset->addField($fieldA);

        $fieldset->addField(['name' => 'b']);

        $fieldC = new Field;
        $this->assertFalse($fieldset->addField($fieldC));

        $this->assertFalse($fieldset->addField('a'));
        $this->assertFalse($fieldset->addField(null));
        $this->assertFalse($fieldset->addField(false));

        $allFields = $fieldset->getAllFields();

        $this->assertSame($fieldA, $allFields['a']);
        $this->assertInstanceOf('\Vhmis\Form\Field', $allFields['b']);
        $this->assertSame('b', $allFields['b']->getName());
    }

    public function testAddFieldSet()
    {
        $fieldset = new FieldSet;

        $fieldSetA = new FieldSet;
        $fieldSetA->setName('a');
        $fieldset->addFieldSet($fieldSetA);

        $fieldset->addFieldSet(['name' => 'b']);

        $fieldSetC = new FieldSet;
        $this->assertFalse($fieldset->addFieldSet($fieldSetC));

        $this->assertFalse($fieldset->addFieldSet('a'));
        $this->assertFalse($fieldset->addFieldSet(null));
        $this->assertFalse($fieldset->addFieldSet(false));
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
