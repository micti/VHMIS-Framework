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
use \Vhmis\I18n\DateTime\Helper\Go;

class GoTest extends \PHPUnit_Framework_TestCase
{
    protected $go;

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

        $this->go = new Go;
    }

    public function testGotoPreviousDay()
    {
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->go->setDateTimeObject($date);
        $this->go->gotoPreviousDay();
        $this->assertEquals(99, $date->getField(1));
        $this->assertEquals(12, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNextDay()
    {
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->go->setDateTimeObject($date);
        $this->go->gotoNextDay();
        $this->assertEquals(100, $date->getField(1));
        $this->assertEquals(1, $date->getField(2));
        $this->assertEquals(2, $date->getField(5));
    }

    public function testSetYesterday()
    {
        $now = time() - 24 * 60 * 60;
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->go->setDateTimeObject($date);
        $this->go->gotoYesterday();
        $this->assertEquals((int) date('Y', $now), $date->getField(1));
        $this->assertEquals((int) date('m', $now), $date->getField(2));
        $this->assertEquals((int) date('d', $now), $date->getField(5));
    }

    public function testSetTomorrow()
    {
        $now = time() + 24 * 60 * 60;
        $date = new DateTime();
        $date->setDate(100, 1, 1);
        $this->go->setDateTimeObject($date);
        $this->go->gotoTomorrow();
        $this->assertEquals((int) date('Y', $now), $date->getField(1));
        $this->assertEquals((int) date('m', $now), $date->getField(2));
        $this->assertEquals((int) date('d', $now), $date->getField(5));
    }

    public function testSetFirstDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 5);
        $this->go->setDateTimeObject($date);
        $this->go->gotoFirstDayOfMonth();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));
    }

    public function testSetLastDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 5);
        $this->go->setDateTimeObject($date);
        $this->go->gotoLastDayOfMonth();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(2, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));
    }

    public function testSetFirstDayOfWeek()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(4);
        $this->go->setDateTimeObject($date);
        $this->go->gotoFirstDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(9, $date->getField(5));

    }

    public function testSetFirstDayOfWeek2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);
        $this->go->gotoFirstDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));
    }

    public function testSetLastDayOfWeek()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(4);
        $this->go->setDateTimeObject($date);
        $this->go->gotoLastDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(15, $date->getField(5));
    }

    public function testSetLastDayOfWeek2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);
        $this->go->gotoLastDayOfWeek();
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(11, $date->getField(5));
    }

    public function testSetNthOfMonthWrong1()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(-1, 3);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthOfMonthWrong2()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(10, 3);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthOfMonthWrong3()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(2, 0);

        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(10, $date->getField(5));
    }

    public function testSetNthDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(0, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(3, $date->getField(5));
    }

    public function testSetNthDayOfMonthOutRangeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(0, 32);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNthDayOfMonthNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(0, -1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }

    public function testSetNthDayOfMonthOutRangeNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(0, -32);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));
    }

    public function testSetNthWeekDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(1, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(6, $date->getField(5));

        $this->go->gotoNthDayOfMonth(2, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(14, $date->getField(5));

        $this->go->gotoNthDayOfMonth(1, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(20, $date->getField(5));

        $this->go->gotoNthDayOfMonth(1, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(27, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithOutRangeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(6, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(25, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(1, -3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(13, $date->getField(5));
    }

    public function testSetNthWeekdayOfMonthWithOutRangeNegativeNth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(2, -5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(7, $date->getField(5));
    }

    public function testSetNthWeekendOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(9, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));

        $this->go->gotoNthDayOfMonth(9, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(6, $date->getField(5));

        $this->go->gotoNthDayOfMonth(9, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(12, $date->getField(5));

        $this->go->gotoNthDayOfMonth(9, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(13, $date->getField(5));

        $this->go->gotoNthDayOfMonth(9, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(19, $date->getField(5));
    }

    public function testSetNthWorkingDayOfMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 7, 10)->setWeekFirstDay(7);
        $this->go->setDateTimeObject($date);

        $this->go->gotoNthDayOfMonth(8, 1);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(1, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 2);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(2, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 3);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(3, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 4);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(4, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 5);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(7, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 20);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(28, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 23);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));

        $this->go->gotoNthDayOfMonth(8, 24);
        $this->assertEquals(2014, $date->getField(1));
        $this->assertEquals(7, $date->getField(2));
        $this->assertEquals(31, $date->getField(5));
    }
}
