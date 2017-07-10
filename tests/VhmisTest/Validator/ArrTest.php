<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Arr;

class ArrTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Validator object
     *
     * @var Arr
     */
    protected $arrValidator;

    public function setUp()
    {
        $this->arrValidator = new Arr();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->arrValidator->isValid([]));

        $this->assertFalse($this->arrValidator->isValid(''));
        $this->assertFalse($this->arrValidator->isValid(new \DateTime()));
        $this->assertFalse($this->arrValidator->isValid(1));
        $this->assertFalse($this->arrValidator->isValid(1.2));
        $this->assertFalse($this->arrValidator->isValid(null));
    }
}
