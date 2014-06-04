<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n;

use Vhmis\I18n\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    protected $date;

    public function setUp()
    {
        if (!class_exists('\IntlCalendar')) {
            $this->markTestSkipped(
                'Intl version 3.0.0 is not available.'
            );
        }

        $this->date = new DateTime('Asia/Ho_Chi_Minh');
    }

    public function testFormat()
    {
        $this->date->setDate(2014, 4, 6)->setTime(0, 12, 34);

        $this->assertEquals('', $this->date->format('sfdsfd'));
        $this->assertEquals('2014', $this->date->getYear());
        $this->assertEquals('05', $this->date->getMonth());
        $this->assertEquals('06', $this->date->getDay());
        $this->assertEquals('00', $this->date->getHour());
        $this->assertEquals('12', $this->date->getMinute());
        $this->assertEquals('34', $this->date->getSecond());
    }

    public function testFormatISO()
    {
        $this->date->setDate(2014, 4, 6);
        $this->assertEquals('2014-05-06', $this->date->formatISODate());

        $this->date->setDate(2014, 4, 6)->setTime(0, 12, 34);
        $this->assertEquals('2014-05-06 00:12:34', $this->date->formatISODateTime());

        $this->date->setDate(2014, 4, 31)->setTime(0, 12, 34);
        $this->assertEquals('2014-05-31 00:12:34', $this->date->formatISODateTime());
        $this->assertEquals('2014-05', $this->date->formatISOYearMonth());
        $this->assertEquals('2014-05-31 00:12:34', $this->date->formatSQLDateTime());
        $this->assertEquals('2014-05-31', $this->date->formatSQLDate());
    }

    public function testTimestamp()
    {
        $this->date->setTimestamp(0);
        $a = new DateTime('Asia/Ho_Chi_Minh', 'chinese');
        $b = new DateTime('Asia/Ho_Chi_Minh', 'hebrew');
        $a->setTimestamp($this->date->getTimestamp());
        $b->setTimestamp($this->date->getTimestamp());

        $this->assertEquals(0, $a->getTimestamp());
        $this->assertEquals(0, $b->getTimestamp());
    }

    public function testAddSecond()
    {
        $this->date->setDate(2014, 0, 31)->setTime(0, 12, 34)->addSecond(31);
        $this->assertEquals('2014-01-31 00:13:05', $this->date->formatISODateTime());
    }

    public function testAddMinute()
    {
        $this->date->setDate(2014, 0, 31)->setTime(0, 12, 34)->addMinute(60);
        $this->assertEquals('2014-01-31 01:12:34', $this->date->formatISODateTime());
    }

    public function testAddHour()
    {
        $this->date->setDate(2014, 0, 31)->setTime(0, 12, 34)->addHour(25);
        $this->assertEquals('2014-02-01 01:12:34', $this->date->formatISODateTime());
    }

    public function testAddDay()
    {
        $this->date->setDate(2014, 0, 31)->addDay(31);
        $this->assertEquals('2014-03-03', $this->date->formatISODate());
        $this->date->addDay(365);
        $this->assertEquals('2015-03-03', $this->date->formatISODate());
    }

    public function testAddMonth()
    {
        $this->date->setDate(2014, 0, 31)->addMonth(1);
        $this->assertEquals('2014-02-28', $this->date->formatISODate());
        $this->date->addMonth(1);
        $this->assertEquals('2014-03-28', $this->date->formatISODate());
    }

    public function testAddYear()
    {
        $this->date->setDate(2012, 1, 29)->addYear(3);
        $this->assertEquals('2015-02-28', $this->date->formatISODate());
        $this->date->addYear(1);
        $this->assertEquals('2016-02-28', $this->date->formatISODate());
    }

    public function testConvert()
    {
        $this->date->setDate(2014, 5, 3);
        $result = array(
            'origin' => '0031-05-06',
            'extend' => '4651-05-06',
            'relate' => '2014-05-06',
        );
        $this->assertEquals($result, $this->date->convertTo('chinese'));
        $this->assertEquals(array(), $this->date->convertTo('vietnames'));
        $result = array(
            'origin' => '0026-06-03',
            'extend' => '2014-06-03',
            'relate' => '0026-06-03',
        );
        $this->assertEquals($result, $this->date->convertTo('japanese'));
        $result = array(
            'origin' => '0031-05-06',
            'extend' => '4347-05-06',
            'relate' => '2014-05-06',
        );
        $this->assertEquals($result, $this->date->convertTo('dangi'));
        $this->date->setDate(1964, 8, 6);
        $result = array(
            'origin' => '0041-08-01',
            'extend' => '4297-08-01',
            'relate' => '1964-08-01',
        );
        $this->assertEquals($result, $this->date->convertTo('dangi'));
    }
}
