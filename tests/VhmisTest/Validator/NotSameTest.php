<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\NotSame;

class NotSameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator object
     *
     * @var NotSame
     */
    protected $notSameValidator;

    public function setUp()
    {
        $this->notSameValidator = new NotSame();
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\MissingOptionException
     */
    public function testMissingOptions()
    {
        $this->assertFalse($this->notSameValidator->isValid([]));
    }

    public function testIsValid()
    {
        $this->notSameValidator->setOptions(['comparedValue' => 'a']);

        $this->assertTrue($this->notSameValidator->isValid(null));

        $this->assertFalse($this->notSameValidator->isValid('a'));
    }
}
