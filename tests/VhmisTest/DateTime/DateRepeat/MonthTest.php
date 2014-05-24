<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Month;

class MonthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Month Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Month
     */
    protected $monthRepeat;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->monthRepeat = new Month('2014-05-12', null, 0, 1);
    }

    public function testEndDate()
    {
        $this->monthRepeat->setStartDate('2014-05-17')->setEndDate(null)->setRepeatTimes(0);
        $this->assertEquals('2100-31-21', $this->monthRepeat->endDate());

        $this->monthRepeat->setStartDate('2014-05-17')->setEndDate('2014-07-09');
        $this->assertEquals('2014-07-09', $this->monthRepeat->endDate());

        $this->monthRepeat->setStartDate('2014-05-17')->setEndDate(null)
            ->setType('day')->setRepeatDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFreq(2);
        $this->assertEquals('2014-09-12', $this->monthRepeat->endDate());

        $this->monthRepeat->setStartDate('2014-05-12')->setEndDate(null)
            ->setType('day')->setRepeatDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFreq(2);
        $this->assertEquals('2014-07-20', $this->monthRepeat->endDate());

        $this->monthRepeat->setStartDate('2014-01-29')->setEndDate(null)
            ->setType('day')->setRepeatDays(array(29, 30, 31))
            ->setRepeatTimes(6)->setFreq(1);
        $this->assertEquals('2014-03-03', $this->monthRepeat->endDate());

        $this->monthRepeat
            ->setStartDate('2014-01-31') // last day of month
            ->setEndDate(null)
            ->setType('relative_day')
            ->setReaptedDayPosition(4)
            ->setReaptedDay(7)
            ->setRepeatTimes(7)
            ->setFreq(1);
        $this->assertEquals('2014-07-31', $this->monthRepeat->endDate());

        $this->monthRepeat
            ->setStartDate('2014-05-24') // fourth saturday of month
            ->setEndDate(null)
            ->setType('relative_day')
            ->setReaptedDayPosition('fourth')
            ->setReaptedDay('saturday')
            ->setRepeatTimes(2)
            ->setFreq(2);
        $this->assertEquals('2014-07-26', $this->monthRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->monthRepeat
            ->setStartDate('2014-05-12')
            ->setEndDate(null)
            ->setType('day')
            ->setRepeatDays(array(12, 17, 20))
            ->setRepeatTimes(6)
            ->setFreq(1);

        $result = array(
            '2014-05-12',
            '2014-05-17',
            '2014-05-20',
            '2014-06-12',
            '2014-06-17',
            '2014-06-20',
        );

        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-12', '2014-06-20'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-12', '2014-09-12'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-06-20'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-09-12'));
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2013-04-20', '2014-01-12'));
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2015-04-20', '2016-09-12'));

        $result = array(
            '2014-05-20',
            '2014-06-12'
        );

        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-18', '2014-06-16'));

        $this->monthRepeat
            ->setStartDate('2014-05-31')
            ->setEndDate(null)
            ->setType('day')
            ->setRepeatDays(array(29, 30, 31))
            ->setRepeatTimes(7)
            ->setFreq(1);
        $result = array(
            '2014-05-31',
            '2014-06-29',
            '2014-06-30',
            '2014-07-01',
            '2014-07-29',
            '2014-07-30',
            '2014-07-31'
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-09-12'));

        $this->monthRepeat
            ->setStartDate('2014-02-28')
            ->setEndDate(null)
            ->setType('day')
            ->setRepeatDays(array(1, 28, 29))
            ->setRepeatTimes(9)
            ->setFreq(1);
        $result = array(
            '2014-02-28',
            '2014-03-01',
            '2014-03-02',
            '2014-03-28',
            '2014-03-29',
            '2014-04-01',
            '2014-04-28',
            '2014-04-29',
            '2014-05-01',
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-02-20', '2014-09-12'));

        $this->monthRepeat
            ->setStartDate('2014-01-31') // last day of month
            ->setEndDate(null)
            ->setType('relative_day')
            ->setReaptedDayPosition(4)
            ->setReaptedDay(7)
            ->setRepeatTimes(7)
            ->setFreq(1);
        $result = array(
            '2014-01-31',
            '2014-02-28',
            '2014-03-31',
            '2014-04-30',
            '2014-05-31',
            '2014-06-30',
            '2014-07-31',
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-01-31', '2014-07-31'));

        $this->monthRepeat
            ->setStartDate('2014-05-24') // fourth saturday of month
            ->setEndDate(null)
            ->setType('relative_day')
            ->setReaptedDayPosition('fourth')
            ->setReaptedDay('saturday')
            ->setRepeatTimes(2)
            ->setFreq(2);
        $result = array(
            '2014-05-24',
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-24', '2014-05-24'));
    }
}
