<?php

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Day;

class DayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Day Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Day
     */
    protected $dayRepeat;

    public function setUp()
    {
        $this->dayRepeat = new Day('2011-01-01', null, 0, 1);
    }

    public function testEndDate()
    {
        $this->dayRepeat->setStartDate('2013-01-01');

        $this->dayRepeat->setEndDate(null)->setRepeatTimes(0);
        $this->assertEquals('2100-31-21', $this->dayRepeat->endDate());

        $this->dayRepeat->setEndDate('2013-02-02')->setRepeatTimes(0);
        $this->assertEquals('2013-02-02', $this->dayRepeat->endDate());

        $this->dayRepeat->setEndDate(null)->setRepeatTimes(1)->setFreq(1);
        $this->assertEquals('2013-01-02', $this->dayRepeat->endDate());

        $this->dayRepeat->setEndDate(null)->setRepeatTimes(7)->setFreq(2);
        $this->assertEquals('2013-01-15', $this->dayRepeat->endDate());
    }
}
