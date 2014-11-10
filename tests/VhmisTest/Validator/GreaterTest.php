<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Greater;

class GreaterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator object
     *
     * @var Greater
     */
    protected $greaterValidator;

    public function setUp()
    {
        $this->greaterValidator = new Greater();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->greaterValidator->setOptions(['compare' => 5])->isValid(6));
        $this->assertFalse($this->greaterValidator->setOptions(['compare' => 4])->isValid(4));
        $this->assertFalse($this->greaterValidator->setOptions(['compare' => 3])->isValid(2));
    }
}
