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

    public function testFormatISO()
    {
        $this->date->set('2014-05-06');
        $this->assertEquals('2014-05-06', $this->date->formatISODate());

        $this->date->set('2014-05-06 00:12:34');
        $this->assertEquals('2014-05-06 00:12:34', $this->date->formatISODateTime());

        $this->date->set('2014-05-31 00:12:34');
        $this->assertEquals('2014-05-31 00:12:34', $this->date->formatISODateTime());
    }

    public function testAddSecond()
    {
        $this->date->set('2014-01-31 00:12:34')->addSecond(31);
        $this->assertEquals('2014-01-31 00:13:05', $this->date->formatISODateTime());
    }

    public function testAddMinute()
    {
        $this->date->set('2014-01-31 00:12:34')->addMinute(60);
        $this->assertEquals('2014-01-31 01:12:34', $this->date->formatISODateTime());
    }

    public function testAddHour()
    {
        $this->date->set('2014-01-31 00:12:34')->addHour(25);
        $this->assertEquals('2014-02-01 01:12:34', $this->date->formatISODateTime());
    }

    public function testAddDay()
    {
        $this->date->set('2014-01-31')->addDay(31);
        $this->assertEquals('2014-03-03', $this->date->formatISODate());
        $this->date->addDay(365);
        $this->assertEquals('2015-03-03', $this->date->formatISODate());
    }

    public function testAddMonth()
    {
        $this->date->set('2014-01-31')->addMonth(1);
        $this->assertEquals('2014-02-28', $this->date->formatISODate());
        $this->date->addMonth(1);
        $this->assertEquals('2014-03-28', $this->date->formatISODate());
    }

    public function testAddYear()
    {
        $this->date->set('2012-02-29')->addYear(3);
        $this->assertEquals('2015-02-28', $this->date->formatISODate());
        $this->date->addYear(1);
        $this->assertEquals('2016-02-28', $this->date->formatISODate());
    }

    public function testConvert()
    {
        $this->date->set('2014-06-02');
        $this->assertEquals('0031-05-05', $this->date->convertTo('chinese'));
        $this->assertEquals('0026-06-02', $this->date->convertTo('japanese'));
    }
}
