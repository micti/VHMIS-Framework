<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Week;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Week Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Week
     */
    protected $weekRepeat;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->weekRepeat = new Week('2011-01-01', null, 0, 1);
    }

    public function testEndDate()
    {
        // 2013-01-01 is tuesday / 2
        $this->weekRepeat->setStartDate('2013-01-01');

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(0);
        $this->assertEquals('2100-31-21', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate('2013-02-02')->setRepeatTimes(0);
        $this->assertEquals('2013-02-02', $this->weekRepeat->endDate());

        // 2014-05-03 is saturday / 6
        $this->weekRepeat->setStartDate('2014-05-03');
        $this->weekRepeat->setStartDayOfWeek('friday');

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(6)->setFreq(1);
        $this->weekRepeat->setRepeatWeekdays(array(1, 2, 6, 0));
        $this->assertEquals('2014-05-11', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(6)->setFreq(3);
        $this->weekRepeat->setRepeatWeekdays(array(1, 2, 6, 0));
        $this->assertEquals('2014-05-25', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(4)->setFreq(1);
        $this->weekRepeat->setRepeatWeekdays(array(1, 2, 6, 0));
        $this->assertEquals('2014-05-06', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(8)->setFreq(2);
        $this->weekRepeat->setRepeatWeekdays(array(1, 2, 6, 0));
        $this->assertEquals('2014-05-20', $this->weekRepeat->endDate());

        // 2014-05-05 is monday / 1
        $this->weekRepeat->setStartDate('2014-05-05');
        $this->weekRepeat->setStartDayOfWeek('friday');

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(7)->setFreq(1);
        $this->weekRepeat->setRepeatWeekdays(array(1, 2, 6, 0));
        $this->assertEquals('2014-05-17', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(2)->setFreq(2);
        $this->weekRepeat->setRepeatWeekdays(array(1));
        $this->assertEquals('2014-05-19', $this->weekRepeat->endDate());

        $this->weekRepeat->setEndDate(null)->setRepeatTimes(2)->setFreq(1);
        $this->weekRepeat->setRepeatWeekdays(array(0, 1));
        $this->assertEquals('2014-05-11', $this->weekRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->weekRepeat->setStartDate('2014-05-03');
        $this->weekRepeat->setStartDayOfWeek('friday');
        $this->weekRepeat->setEndDate('2014-05-11')->setFreq(1)->setRepeatWeekdays(array(1, 2, 6, 0));

        $result = array(
            '2014-05-03',
            '2014-05-04',
            '2014-05-05',
            '2014-05-06',
            '2014-05-10',
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-11'));

        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2014-05-01', '2014-05-02'));
        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2014-05-12', '2017-05-03'));

        $result = array(
            '2014-05-03',
            '2014-05-04',
            '2014-05-05',
            '2014-05-06'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-09'));
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-06'));

        $result = array(
            '2014-05-04',
            '2014-05-05'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-04', '2014-05-05'));

        $this->weekRepeat->setStartDate('2014-05-04');
        $result = array(
            '2014-05-10',
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-09', '2014-05-14'));

        $result = array(
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-11', '2014-05-11'));
    }
}
