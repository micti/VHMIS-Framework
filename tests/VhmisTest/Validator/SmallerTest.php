<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Smaller;

class SmallerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator object
     *
     * @var Smaller
     */
    protected $smallerValidator;

    public function setUp()
    {
        $this->smallerValidator = new Smaller();
    }

    public function testIsValid()
    {
        $this->assertFalse($this->smallerValidator->setOptions(['compare' => 5])->isValid(6));
        $this->assertFalse($this->smallerValidator->setOptions(['compare' => 4])->isValid(4));
        $this->assertTrue($this->smallerValidator->setOptions(['compare' => 3])->isValid(2));
    }
}
