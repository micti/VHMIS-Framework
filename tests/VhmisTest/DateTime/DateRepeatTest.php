<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime;

use Vhmis\DateTime\DateRepeat;

class DateRepeatTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var DateRepeat
     */
    protected $repeat;

    public function setUp()
    {
        $this->repeat = new DateRepeat;
    }

    public function testGetRepeat()
    {
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\AbstractRepeat', $this->repeat->getRepeat());
    }

    public function testGetRule()
    {
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Rule', $this->repeat->getRule());
    }
}
