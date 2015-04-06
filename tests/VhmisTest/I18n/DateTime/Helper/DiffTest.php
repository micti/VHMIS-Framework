<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\Helper;

use \Vhmis\I18n\DateTime\DateTime;
use \Vhmis\I18n\DateTime\Helper\Diff;

class DiffTest extends \PHPUnit_Framework_TestCase
{

    protected $diff;

    public function setUp()
    {
        if (!extension_loaded('intl')) {
            $this->markTestSkipped(
                    'Intl ext is not available.'
            );
        }

        if (!class_exists('\IntlCalendar')) {
            $this->markTestSkipped(
                    'Intl version 3.0.0 is not available.'
            );
        }
        $this->diff = new Diff;
    }

    public function testDiff()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $result = array(
            'era' => false,
            'year' => 1,
            'month' => 4,
            'day' => 29,
            'hour' => 4,
            'minute' => 59,
            'second' => 58,
            'millisecond' => 999
        );

        $this->assertEquals($result, $this->diff->diff($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $result = array(
            'era' => 0,
            'year' => 1,
            'month' => 3,
            'day' => 0,
            'hour' => 0,
            'minute' => 0,
            'second' => 0,
            'millisecond' => 0
        );

        $this->assertEquals($result, $this->diff->diff($a));
    }

    public function testDiffYear()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(1, $this->diff->diffYear($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(1, $this->diff->diffYear($a));
    }

    public function testDiffMonth()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(16, $this->diff->diffMonth($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(15, $this->diff->diffMonth($a));
    }

    public function testDiffDay()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(516, $this->diff->diffDay($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(443, $this->diff->diffDay($a));
    }

    public function testDiffHour()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(12388, $this->diff->diffHour($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(10632, $this->diff->diffHour($a));
    }

    public function testDiffMinute()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(743339, $this->diff->diffMinute($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(637920, $this->diff->diffMinute($a));
    }

    public function testDiffSecond()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(44600398, $this->diff->diffSecond($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(38275200, $this->diff->diffSecond($a));
    }

    public function testDiffMillisecond()
    {
        $date = new DateTime;
        $date->setDate(2014, 12, 12)->setTime(14, 13, 11)->setField(14, 124);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime;
        $a->setDate(2016, 5, 11)->setTime(19, 13, 10)->setField(14, 123);

        $this->assertEquals(false, $this->diff->diffMillisecond($a));

        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 0);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 12)->setTime(14, 13, 11)->setField(14, 0);
        $a->setField(0, 78);

        $this->assertEquals(false, $this->diff->diffMillisecond($a));
    }

    public function testDiffAbsoluteYear()
    {
        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 3)->setTime(17, 12, 11);
        $a->setField(0, 79);

        $this->assertEquals(61, $this->diff->diffAbsoluteYear($a));

        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 11);

        $this->assertEquals(5, $this->diff->diffAbsoluteYear($a));
    }

    public function testDiffAbsoluteMonth()
    {
        $date = new DateTime(null, 'chinese');
        $date->setDate(30, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $date->setField(0, 78);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime(null, 'chinese');
        $a->setDate(31, 10, 3)->setTime(17, 12, 11);
        $date->setField(0, 78);

        $this->assertEquals(15, $this->diff->diffAbsoluteMonth($a));

        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 11);

        $this->assertEquals(62, $this->diff->diffAbsoluteMonth($a));
    }

    public function testDiffAbsoluteWeek()
    {
        $date = new DateTime();
        $date->setWeekFirstDay(1)->setDate(2014, 8, 10)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setWeekFirstDay(1)->setDate(2014, 8, 11)->setTime(17, 12, 11);

        $this->assertEquals(0, $this->diff->diffAbsoluteWeek($a));

        $date->setWeekFirstDay(2);

        $this->assertEquals(1, $this->diff->diffAbsoluteWeek($a));
    }

    public function testDiffAbsoluteDay()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 11);

        $this->assertEquals(1888, $this->diff->diffAbsoluteDay($a));
    }

    public function testDiffAbsoluteHour()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 11);

        $this->assertEquals(45315, $this->diff->diffAbsoluteHour($a));
    }

    public function testDiffAbsoluteMinute()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 11);

        $this->assertEquals(2718899, $this->diff->diffAbsoluteMinute($a));
    }

    public function testDiffAbsoluteSecond()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 16);

        $this->assertEquals(163133945, $this->diff->diffAbsoluteSecond($a));
    }

    public function testDiffAbsoluteMillisecond()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 16)->setField(14, 0);

        $this->assertEquals(163133944324.0, $this->diff->diffAbsoluteMillisecond($a));
    }

    public function testDiffAbsolute()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(14, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(17, 12, 16)->setField(14, 0);

        $result = array(
            'era' => 0,
            'year' => 5,
            'month' => 62,
            'day' => 1888,
            'week' => 269,
            'hour' => 45315,
            'minute' => 2718899,
            'second' => 163133945,
            'millisecond' => 163133944324.0
        );

        $this->assertEquals($result, $this->diff->diffAbsolute($a));
    }

    public function testDiffCheck()
    {
        $date = new DateTime();
        $date->setDate(2013, 8, 12)->setTime(17, 13, 11)->setField(14, 676);
        $this->diff->setDateTimeObject($date);

        $a = new DateTime();
        $a->setDate(2018, 10, 13)->setTime(5, 12, 16)->setField(14, 676);

        $result = array(
            'era' => false,
            'year' => true,
            'month' => true,
            'day' => true,
            'week' => false,
            'am_pm' => true,
            'hour_am_pm' => false,
            'hour' => true,
            'minute' => true,
            'second' => true,
            'millisecond' => false
        );

        $this->assertEquals($result, $this->diff->diffCheck($a));
    }
}
