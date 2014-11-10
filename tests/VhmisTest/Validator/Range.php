<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Range;

class RangeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator object
     *
     * @var Range
     */
    protected $rangeValidator;

    public function setUp()
    {
        $this->rangeValidator = new Range();
    }

    public function testIsValid()
    {
        $this->rangeValidator->setOptions(['min' => 5, 'max' => 9]);

        $this->assertFalse($this->rangeValidator->isValid(2));
        $this->assertTrue($this->rangeValidator->isValid(5));
        $this->assertTrue($this->rangeValidator->isValid(7));
        $this->assertTrue($this->rangeValidator->isValid(9));
        $this->assertFalse($this->rangeValidator->isValid(10));
    }
}
