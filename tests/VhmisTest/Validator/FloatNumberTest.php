<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\FloatNumber;

class FloatNumberTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Validator object
     *
     * @var Float
     */
    protected $floatValidator;

    public function setUp()
    {
        locale_set_default('en_US');
        $this->floatValidator = new FloatNumber();
    }

    public function testLocaleOption()
    {
        $this->floatValidator->reset();
        $options = $this->floatValidator->getOptions();
        $this->assertSame('en_US', $options['locale']);
    }

    public function testIsValid()
    {
        $this->assertSame(false, $this->floatValidator->isValid(null));
        $this->assertSame(false, $this->floatValidator->isValid(''));
        $this->assertSame(false, $this->floatValidator->isValid([]));
        $this->assertSame(true, $this->floatValidator->isValid(1));
        $this->assertSame(true, $this->floatValidator->isValid(-1));
        $this->assertSame(true, $this->floatValidator->isValid(-1.66));
        $this->assertSame(true, $this->floatValidator->isValid(1.0));
        $this->assertSame(false, $this->floatValidator->isValid('a'));
        $this->assertSame(true, $this->floatValidator->isValid('1'));
        $this->assertSame(true, $this->floatValidator->isValid('1,000.34000'));
        $this->assertSame(true, $this->floatValidator->isValid('-1,000.34'));
        $this->assertSame(true, $this->floatValidator->isValid('-1,000.'));
        $this->assertSame(false, $this->floatValidator->isValid('-1.000,34'));

        $this->floatValidator->setOptions(['locale' => 'de']);
        $this->assertSame(false, $this->floatValidator->isValid('10000.00'));
        $this->assertSame(true, $this->floatValidator->isValid('10.000,'));
    }
}
