<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Int;

class IntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validator object
     *
     * @var Int
     */
    protected $intValidator;

    public function setUp()
    {
        locale_set_default('en_US');
        $this->intValidator = new Int();
    }

    public function testLocaleOption()
    {
        $this->intValidator->reset();
        $options = $this->intValidator->getOptions();
        $this->assertSame('en_US', $options['locale']);
    }

    public function testValid()
    {
        $this->assertSame(false, $this->intValidator->isValid([]));
        $this->assertSame(true, $this->intValidator->isValid(1));
        $this->assertSame(true, $this->intValidator->isValid(-1));
        $this->assertSame(true, $this->intValidator->isValid(1.0));
        $this->assertSame(false, $this->intValidator->isValid('a'));
        $this->assertSame(true, $this->intValidator->isValid('1'));
        $this->assertSame(true, $this->intValidator->isValid('1,000'));
        $this->assertSame(false, $this->intValidator->isValid('-1,000.0000'));

        $this->intValidator->setOptions(['locale' => 'de']);
        $this->assertSame(false, $this->intValidator->isValid('10 000'));
        $this->assertSame(true, $this->intValidator->isValid('10.000'));
    }
}
