<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Form;

use Vhmis\Form\Field;

class FieldTest extends \PHPUnit\Framework\TestCase
{

    public function testSetAndGetName()
    {
        $field = new Field();
        $this->assertSame('a', $field->setName('a')->getName());
    }

    public function testSetAndGetValue()
    {
        $field = new Field();
        $this->assertSame('a', $field->setValue('a')->getValue());
    }

    public function testSetAndGetType()
    {
        $field = new Field();
        $this->assertSame('a', $field->setType('a')->getType());
    }

    public function testAllowNull()
    {
        $field = new Field();
        $this->assertSame(false, $field->isAllowedNull());
        $field->allowNull();
        $this->assertSame(true, $field->isAllowedNull());
    }

    public function testAllowEmpty()
    {
        $field = new Field();
        $this->assertSame(false, $field->isAllowedEmpty());
        $field->allowEmpty();
        $this->assertSame(true, $field->isAllowedEmpty());
    }
}
