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

class SetTest extends \PHPUnit\Framework\TestCase
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
}
