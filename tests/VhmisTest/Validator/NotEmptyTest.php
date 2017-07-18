<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\NotEmpty;

class NotEmptyTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Validator object
     *
     * @var NotEmpty
     */
    protected $notEmptyValidator;

    public function setUp()
    {
        $this->notEmptyValidator = new NotEmpty();
    }

    public function testIsValid()
    {
        $this->assertFalse($this->notEmptyValidator->isValid(''));
        $this->assertFalse($this->notEmptyValidator->isValid([]));
        $this->assertFalse($this->notEmptyValidator->isValid(null));
        $this->assertTrue($this->notEmptyValidator->isValid(new \DateTime()));
        $this->assertTrue($this->notEmptyValidator->isValid(1));
        $this->assertTrue($this->notEmptyValidator->isValid(1.2));
        $this->assertTrue($this->notEmptyValidator->isValid(' '));

        $this->assertFalse($this->notEmptyValidator->isValid(''));
    }
}
