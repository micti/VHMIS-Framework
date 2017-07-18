<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Same;

class SameTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Validator object
     *
     * @var NotSame
     */
    protected $sameValidator;

    public function setUp()
    {
        $this->sameValidator = new Same();
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\MissingOptionException
     */
    public function testMissingOptions()
    {
        $this->assertFalse($this->sameValidator->isValid([]));
    }

    public function testIsValid()
    {
        $this->sameValidator->setOptions(['comparedValue' => 'a']);

        $this->assertFalse($this->sameValidator->isValid(null));

        $this->assertTrue($this->sameValidator->isValid('a'));
    }
}
