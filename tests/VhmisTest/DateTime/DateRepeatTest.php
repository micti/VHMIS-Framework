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
        $rule = $this->repeat->getRule();
        $rule->setRepeatByDay();
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Day', $this->repeat->getRepeat());
        $rule->setRepeatByWeek();
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Week', $this->repeat->getRepeat());
        $rule->setRepeatByMonth();
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Month', $this->repeat->getRepeat());
        $rule->setRepeatByYear();
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Year', $this->repeat->getRepeat());
    }

    public function testEndDate()
    {
        $this->assertEquals('2100-12-31', $this->repeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->assertEquals(array(), $this->repeat->repeatedDates('2014-01-01', '2015-02-02'));
    }

    public function testGetRule()
    {
        $this->assertInstanceOf('Vhmis\DateTime\DateRepeat\Rule', $this->repeat->getRule());
    }

    public function testSetStartDateOfWeek()
    {
        $repeat = new DateRepeat;
        $repeat->setStartDayOfWeek(4);
        $repeat->getRule()->setRepeatByWeek()->setBaseDate('2014-05-28')->setRepeatWeekdays(array(3, 5))
            ->setRepeatTimes(2)->setFrequency(2);
        $this->assertEquals('2014-06-06', $repeat->endDate());

        $repeat->setStartDayOfWeek(1);
        $this->assertEquals('2014-05-30', $repeat->endDate());
    }
}
