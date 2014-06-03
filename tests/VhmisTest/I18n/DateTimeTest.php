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
        
    }
}
