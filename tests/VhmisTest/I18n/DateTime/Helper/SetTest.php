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

        $this->set->setDate($date);

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

    public function testSetSecond()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDate($date);
        $this->set->setSecond(12);
        $this->assertEquals('23:15:12', $date->getTime());
    }

    public function testSetMinute()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDate($date);
        $this->set->setMinute(12);
        $this->assertEquals('23:12:54', $date->getTime());
    }

    public function testSetHour()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $this->set->setDate($date);
        $this->set->setHour(12);
        $this->assertEquals('12:15:54', $date->getTime());
    }

    public function atestSetDay()
    {
        $date = new DateTime();
        $date->setDate(2014, 2, 28);
        $this->set->setDate($date);
        $this->set->setDay(13);
        $this->assertEquals('2014-02-13', $date->getDate());
        $this->set->setDay(29);
        $this->assertEquals('2014-02-28', $date->getDate());
        $this->set->setDay(32);
        $this->assertEquals('2014-02-28', $date->getDate());
        $this->set->setDay(-5);
        $this->assertEquals('2014-02-28', $date->getDate());
    }

    public function afdfdtestSetMonth()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 20); // 2014 9 leap
        $date->setField(22, 1); // 2014 9 leap
        $this->assertEquals(29, $date->getActualMaximumValueOfField(5));
        $this->set->setDate($date);
        $this->set->setMonth(12);
        $this->assertEquals('0031-12-20', $date->getDate());
        $date->setDate(31, 9, 30); // 2014 9 no leap
        $date->setField(22, 0); // 2014 9 no leap
        $this->set->setMonth(11);
        $this->assertEquals('0031-11-29', $date->getDate());
    }

    public function testSetLeapMonth()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 6, 30); // 2014 9 leap
        $date->setField(22, 0); // 2014 9 leap
        $this->set->setDate($date);
        $this->set->setLeapMonth(4);
        $this->assertEquals('0031-06-30', $date->getDate());
        $this->assertEquals(0, $date->getField(22));
        $this->set->setLeapMonth(9);
        $this->assertEquals('0031-09-29', $date->getDate());
        $this->assertEquals(1, $date->getField(22));
        $this->set->setLeapMonth(11);
        $this->assertEquals('0031-09-29', $date->getDate());
        $this->assertEquals(1, $date->getField(22));
        $this->set->setMonth(11);
        $this->assertEquals('0031-11-29', $date->getDate());
        $this->assertEquals(0, $date->getField(22));
    }

    public function testSetYear()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 29); // 2014 7 leap
        $date->setField(22, 1); // 2014 7 leap
        $this->set->setDate($date);
        $this->set->setYear(33);
        $this->assertEquals('0033-09-29', $date->getDate());
        $this->assertEquals(0, $date->getField(22));
    }

    public function testSetEra()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 9, 29); // 2014 7 leap
        $date->setField(22, 1); // 2014 7 leap
        $this->set->setDate($date);
        $this->set->setEra(88);
        $this->assertEquals('0031-09-29', $date->getDate());
        $this->assertEquals(88, $date->getField(0));
    }
}
