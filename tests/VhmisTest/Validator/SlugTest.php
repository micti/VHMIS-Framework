<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\Slug;

class SlugTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Validator object
     *
     * @var Float
     */
    protected $slugValidator;

    public function setUp()
    {
        $this->slugValidator = new Slug();
    }

    public function testIsValid()
    {
        $this->assertSame(false, $this->slugValidator->isValid(null));
        $this->assertSame(false, $this->slugValidator->isValid(2));
        $this->assertSame(false, $this->slugValidator->isValid([]));
        $this->assertSame(true, $this->slugValidator->isValid('aaa-aaa-vcc-vsdsds'));
        $this->assertSame(false, $this->slugValidator->isValid('aaa-aaa-Â -vcc-vsdsds'));
        $this->assertSame(true, $this->slugValidator->isValid('aaa'));
        $this->assertSame(true, $this->slugValidator->isValid('435454'));
        $this->assertSame(true, $this->slugValidator->isValid('-'));
        $this->assertSame(true, $this->slugValidator->isValid('454sdsfd'));
        $this->assertSame(true, $this->slugValidator->isValid('454-4445454'));
        $this->assertSame(true, $this->slugValidator->isValid('65763-fdfd'));
    }
}
