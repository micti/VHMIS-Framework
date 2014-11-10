<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\NotNull;

class NotNullTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Validator object
     *
     * @var NotNull
     */
    protected $notNullValidator;

    public function setUp()
    {
        $this->notNullValidator = new NotNull();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->notNullValidator->isValid([]));
        $this->assertTrue($this->notNullValidator->isValid(''));
        $this->assertTrue($this->notNullValidator->isValid(new \DateTime()));
        $this->assertTrue($this->notNullValidator->isValid(1));
        $this->assertTrue($this->notNullValidator->isValid(1.2));

        $this->assertFalse($this->notNullValidator->isValid(null));
    }
}
