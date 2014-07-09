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
use \Vhmis\I18n\DateTime\Helper\Set;

class SetTest extends \PHPUnit_Framework_TestCase
{
    protected $set;

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
        $this->set = new Set;
    }

    public function testInvoke()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);

        $this->set->setDateTimeObject($date);

        $a = $this->set;
        $a('setDay', array(1));
        $this->assertEquals(1, $date->getField(5));
    }

    public function testWrongParams()
    {
        $a = $this->set;
        $this->assertEquals(null, $a('setDay', 1));
    }

    public function testWrongCountParams()
    {
        $a = $this->set;
        $this->assertEquals(null, $a('setDay', array(1, 2)));
    }

    public function testWrongMethod()
    {
        $a = $this->set;
        $this->assertEquals(null, $a('setAAAA', array(1)));
    }

    public function testSetMillisecond()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDateTimeObject($date);
        $this->set->setMillisecond(125);
        $this->assertEquals(125, $date->getField(14));
    }

    public function testSetSecond()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDateTimeObject($date);

        $this->set->setSecond(12);
        $this->assertEquals(23, $date->getField(11));
        $this->assertEquals(15, $date->getField(12));
        $this->assertEquals(12, $date->getField(13));
    }

    public function testSetMinute()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDateTimeObject($date);

        $this->set->setMinute(12);
        $this->assertEquals(23, $date->getField(11));
        $this->assertEquals(12, $date->getField(12));
        $this->assertEquals(54, $date->getField(13));
    }

    public function testSetHour()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDateTimeObject($date);

        $this->set->setHour(12);
        $this->assertEquals(12, $date->getField(11));
        $this->assertEquals(15, $date->getField(12));
        $this->assertEquals(54, $date->getField(13));
    }

    public function testSetDay()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 28);
        $this->set->setDateTimeObject($date);
        $this->set->setDay(13);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(13, $date->getField(5));

        $this->set->setDay(29);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));

        $this->set->setDay(32);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));

        $this->set->setDay(-5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));
    }

    public function testSetMonthOut()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 28);
        $this->set->setDateTimeObject($date);

        $this->set->setMonth(14);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));
    }

    public function testSetMonth()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 20); // 2014 9 leap
        $date->setField(22, 1); // 2014 9 leap
        $this->set->setDateTimeObject($date);

        $this->set->setMonth(12);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(12, $date->getField(2));
        $this->assertEquals(20, $date->getField(5));

        $date->setDate(31, 9, 30); // 2014 9 no leap
        $date->setField(22, 0); // 2014 9 no leap

        $this->set->setMonth(11);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(11, $date->getField(2));
        $this->assertEquals(29, $date->getField(5));
    }

    public function testSetLeapMonth()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 6, 30); // 2014 9 leap
        $date->setField(22, 0); // 2014 9 leap
        $this->set->setDateTimeObject($date);

        $this->set->setLeapMonth(4);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(6, $date->getField(2));
        $this->assertEquals(30, $date->getField(5));
        $this->assertEquals(0, $date->getField(22));

        $this->set->setLeapMonth(9);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(9, $date->getField(2));
        $this->assertEquals(29, $date->getField(5));
        $this->assertEquals(1, $date->getField(22));

        $this->set->setLeapMonth(11);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(9, $date->getField(2));
        $this->assertEquals(29, $date->getField(5));
        $this->assertEquals(1, $date->getField(22));

        $this->set->setMonth(11);
        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(11, $date->getField(2));
        $this->assertEquals(29, $date->getField(5));
        $this->assertEquals(0, $date->getField(22));
    }

    public function testSetYearOut()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 28);
        $this->set->setDateTimeObject($date);
        $this->set->setYear(-1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));
    }

    public function testSetYear()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 29); // 2014 7 leap
        $date->setField(22, 1); // 2014 7 leap
        $this->set->setDateTimeObject($date);
        $this->set->setYear(33);
        $this->assertEquals('0033-09-29', $date->getDate());
        $this->assertEquals(0, $date->getField(22));
    }

    public function testSetEra()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 29); // 2014 7 leap
        $date->setField(22, 1); // 2014 7 leap
        $this->set->setDateTimeObject($date);
        $this->set->setEra(88);
        $this->assertEquals('0031-09-29', $date->getDate());
        $this->assertEquals(88, $date->getField(0));
    }

    public function testSetNow()
    {
        $now = time();
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->set->setDateTimeObject($date);
        $this->set->setNow();
        $this->assertEquals((int) date('Y', $now), $date->getField(1));
        $this->assertEquals((int) date('m', $now), $date->getField(2));
        $this->assertEquals((int) date('d', $now), $date->getField(5));
    }

    public function testSetPreviousDay()
    {
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->set->setDateTimeObject($date);
        $this->set->setPreviousDay();
        $this->assertEquals(99, $date->getField(1));
        $this->assertEquals(12, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNextDay()
    {
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->set->setDateTimeObject($date);
        $this->set->setNextDay();
        $this->assertEquals(100, $date->getField(1));
        $this->assertEquals(1, $date->getField(2));
        $this->assertEquals(2, $date->getField(5));
    }

    public function testSetYesterday()
    {
        $now = time() - 24 * 60 * 60;
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->set->setDateTimeObject($date);
        $this->set->setYesterday();
        $this->assertEquals((int) date('Y', $now), $date->getField(1));
        $this->assertEquals((int) date('m', $now), $date->getField(2));
        $this->assertEquals((int) date('d', $now), $date->getField(5));
    }

    public function testSetTomorrow()
    {
        $now = time() + 24 * 60 * 60;
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->set->setDateTimeObject($date);
        $this->set->setTomorrow();
        $this->assertEquals((int) date('Y', $now), $date->getField(1));
        $this->assertEquals((int) date('m', $now), $date->getField(2));
        $this->assertEquals((int) date('d', $now), $date->getField(5));
    }

    public function testSetFirstDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 5);
        $this->set->setDateTimeObject($date);
        $this->set->setFirstDayOfMonth();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));
    }

    public function testSetLastDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 5);
        $this->set->setDateTimeObject($date);
        $this->set->setLastDayOfMonth();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));
    }

    public function testSetFirstDayOfWeek()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(4);
        $this->set->setDateTimeObject($date);
        $this->set->setFirstDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(9, $date->getField(5));

    }

    public function testSetFirstDayOfWeek2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);
        $this->set->setFirstDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));
    }

    public function testSetLastDayOfWeek()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(4);
        $this->set->setDateTimeObject($date);
        $this->set->setLastDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(15, $date->getField(5));
    }

    public function testSetLastDayOfWeek2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);
        $this->set->setLastDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(11, $date->getField(5));
    }

    public function testSetNthOfMonthWrong1()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(-1, 3);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthOfMonthWrong2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(10, 3);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthOfMonthWrong3()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(2, 0);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(0, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(3, $date->getField(5));
    }

    public function testSetNthDayOfMonthOutRangeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(0, 32);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNthDayOfMonthNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(0, -1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNthDayOfMonthOutRangeNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(0, -32);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));
    }

    public function testSetNthWeekDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(1, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(6, $date->getField(5));

        $this->set->setNthOfMonth(2, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(14, $date->getField(5));

        $this->set->setNthOfMonth(1, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(20, $date->getField(5));

        $this->set->setNthOfMonth(1, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(27, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithOutRangeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(6, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(25, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(1, -3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(13, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithOutRangeNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(2, -5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(7, $date->getField(5));
    }

    public function testSetNthWeekendOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(9, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));

        $this->set->setNthOfMonth(9, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(6, $date->getField(5));

        $this->set->setNthOfMonth(9, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(12, $date->getField(5));

        $this->set->setNthOfMonth(9, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(13, $date->getField(5));

        $this->set->setNthOfMonth(9, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(19, $date->getField(5));
    }

    public function testSetNthWorkingDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->set->setDateTimeObject($date);

        $this->set->setNthOfMonth(8, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));

        $this->set->setNthOfMonth(8, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(2, $date->getField(5));

        $this->set->setNthOfMonth(8, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(3, $date->getField(5));

        $this->set->setNthOfMonth(8, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(4, $date->getField(5));

        $this->set->setNthOfMonth(8, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(7, $date->getField(5));

        $this->set->setNthOfMonth(8, 20);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));

        $this->set->setNthOfMonth(8, 23);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));

        $this->set->setNthOfMonth(8, 24);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }
}
