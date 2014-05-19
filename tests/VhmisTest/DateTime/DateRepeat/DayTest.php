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

    public function testRepeatedDates()
    {
        $this->dayRepeat->setStartDate('2013-01-01')->setEndDate('2013-02-01')->setFreq(2);
        $repeatedDates = $this->dayRepeat->repeatedDates();
        $result = array(
            '2013-01-01',
            '2013-01-03',
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11',
            '2013-01-13',
            '2013-01-15',
            '2013-01-17',
            '2013-01-19',
            '2013-01-21',
            '2013-01-23',
            '2013-01-25',
            '2013-01-27',
            '2013-01-29',
            '2013-01-31'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-10', null);
        $result = array(
            '2013-01-11',
            '2013-01-13',
            '2013-01-15',
            '2013-01-17',
            '2013-01-19',
            '2013-01-21',
            '2013-01-23',
            '2013-01-25',
            '2013-01-27',
            '2013-01-29',
            '2013-01-31'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates(null, '2013-01-10');
        $result = array(
            '2013-01-01',
            '2013-01-03',
            '2013-01-05',
            '2013-01-07',
            '2013-01-09'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-12');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-04', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates(null, '2012-12-31');
        $result = array();
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-02-02', null);
        $result = array();
        $this->assertEquals($result, $repeatedDates);
    }
}
