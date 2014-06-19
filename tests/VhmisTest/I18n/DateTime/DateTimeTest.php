<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n;

use \Vhmis\I18n\DateTime\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    protected $date;

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

        $this->date = new DateTime('Asia/Ho_Chi_Minh');
    }

    public function testConstruct()
    {
        // Default
        $d = new DateTime();
        $this->assertInstanceOf('\Vhmis\I18n\DateTime\DateTime', $d);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testConstructException()
    {
        // Wrong timezone
        new DateTime('Hahaha');
    }

    public function testWrongMagicCall()
    {
        $this->assertEquals(null, $this->date->abc());
    }

    public function testWrongGetMagicCall()
    {
        $this->assertEquals(null, $this->date->getYear('a'));
    }

    public function testWrongSetMagicCall()
    {
        $this->assertEquals(null, $this->date->setHour());
    }

    public function testWrongAddMagicCall()
    {
        $this->assertEquals(null, $this->date->addYear(1, 4));
    }

    public function testSetAndGetDate()
    {
        $this->date->setDate(1, 1, 1);
        $this->assertEquals('0001-01-01', $this->date->getDate());

        $this->date->setDate(2014, 2, 28);
        $this->assertEquals('2014-02-28', $this->date->getDate());

        $this->date->setDate(2014, 0, 28);
        $this->assertEquals('2013-12-28', $this->date->getDate());

        $this->date->setDate(2014, -1, 28);
        $this->assertEquals('2013-11-28', $this->date->getDate());
    }

    public function testSetAndGetTime()
    {
        $this->date->setTime(1, 1, 1);
        $this->assertEquals('01:01:01', $this->date->getTime());

        $this->date->setTime(0, 0, 0);
        $this->assertEquals('00:00:00', $this->date->getTime());

        $this->date->setTime(-1, -1, -1);
        $this->assertEquals('22:58:59', $this->date->getTime());
    }

    public function testGetDateTime()
    {
        $this->date->setDate(11111, 2, 1); // move to 11111-02-01
        $this->date->setTime(-1, -1, -1); // move back 11111-01-31

        $this->assertEquals('11111-01-31 22:58:59', $this->date->getDateTime());

        $this->date->setDate(2014, 6, 6); // move to 11111-02-01
        $this->date->setTime(8, 8, 8); // move back 11111-01-31

        $this->assertEquals('2014-06-06 08:08:08', $this->date->getDateTime());
    }

    public function testSetAndGetTimestamp()
    {
        $d = new DateTime('GMT+01:00');
        $i = 1402023242; // 2014-06-06 02:54:02 utc
        $d->setTimestamp($i);
        $this->assertEquals('2014-06-06 03:54:02', $d->getDateTime());
        $this->assertEquals(1402023242, $d->getTimestamp());

        $this->date->setTimestamp(0);
        $a = new DateTime('Asia/Ho_Chi_Minh', 'chinese');
        $b = new DateTime('Asia/Ho_Chi_Minh', 'hebrew');
        $a->setTimestamp($this->date->getTimestamp());
        $b->setTimestamp($this->date->getTimestamp());

        $this->assertEquals(0, $a->getTimestamp());
        $this->assertEquals(0, $b->getTimestamp());
    }

    public function testSetDateWithExtendYear()
    {
        $this->date->setDateWithExtenedYear(-2012, 1, 1);
        $this->assertEquals('2013-01-01', $this->date->getDate());
    }

    public function testModifyDate()
    {
        $this->date->setDate(2012, 2, 29);
        $this->date->modify('2014-02-28');
        $this->assertEquals('2014-02-28', $this->date->getDate());
    }

    public function testModifyTime()
    {
        $this->date->setDate(2012, 2, 29)->setTime(1, 2, 3);
        $this->date->modify('13:15:15');
        $this->assertEquals('13:15:15', $this->date->getTime());
    }

    public function testModifyDateTime()
    {
        $this->date->setDate(2012, 2, 29)->setTime(1, 2, 3);
        $this->date->modify('2014-02-28 13:15:15');
        $this->assertEquals('2014-02-28 13:15:15', $this->date->getDateTime());
    }

    public function testModifyDateTimeOutRange()
    {
        $this->date->setDate(2012, 2, 29)->setTime(1, 2, 3);
        $this->date->modify('2014-02-29 26:15:15');
        $this->assertEquals('2014-03-02 02:15:15', $this->date->getDateTime());
    }

    public function testModifyWrongDateTime()
    {
        $this->date->setDate(2012, 2, 29)->setTime(1, 2, 3);
        $this->date->modify('2014-024-29 26:15:15');
        $this->assertEquals('2012-02-29 01:02:03', $this->date->getDateTime());
    }

    public function testAddSecond()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34)->addSecond(31);
        $this->assertEquals('2014-01-31 00:13:05', $this->date->getDateTime());
        $this->date->addSecond(-61);
        $this->assertEquals('2014-01-31 00:12:04', $this->date->getDateTime());
    }

    public function testAddMinute()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34)->addMinute(60);
        $this->assertEquals('2014-01-31 01:12:34', $this->date->getDateTime());
        $this->date->addMinute(-23);
        $this->assertEquals('2014-01-31 00:49:34', $this->date->getDateTime());
    }

    public function testAddHour()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34)->addHour(25);
        $this->assertEquals('2014-02-01 01:12:34', $this->date->getDateTime());
        $this->date->addHour(-2);
        $this->assertEquals('2014-01-31 23:12:34', $this->date->getDateTime());
    }

    public function testAddDay()
    {
        $this->date->setDate(2014, 1, 31)->addDay(31);
        $this->assertEquals('2014-03-03', $this->date->getDate());
        $this->date->addDay(365);
        $this->assertEquals('2015-03-03', $this->date->getDate());
        $this->date->addDay(-3);
        $this->assertEquals('2015-02-28', $this->date->getDate());
    }

    public function testAddWeek()
    {
        $this->date->setDate(2014, 1, 31)->addWeek(2);
        $this->assertEquals('2014-02-14', $this->date->getDate());
        $this->date->setDate(2014, 12, 11)->addWeek(4);
        $this->assertEquals('2015-01-08', $this->date->getDate());
    }

    public function testAddMonth()
    {
        $this->date->setDate(2014, 1, 31)->addMonth(1);
        $this->assertEquals('2014-02-28', $this->date->getDate());
        $this->date->addMonth(1);
        $this->assertEquals('2014-03-28', $this->date->getDate());
        $this->date->addMonth(-14);
        $this->assertEquals('2013-01-28', $this->date->getDate());
    }

    public function testAddYear()
    {
        $this->date->setDate(2012, 2, 29)->addYear(3);
        $this->assertEquals('2015-02-28', $this->date->getDate());
        $this->date->addYear(1);
        $this->assertEquals('2016-02-28', $this->date->getDate());
        $this->date->addYear(-6);
        $this->assertEquals('2010-02-28', $this->date->getDate());
    }

    public function testAddEra()
    {
        $a = new DateTime('Asia/Ho_Chi_Minh', 'chinese');
        $a->setEra(80);
        $a->addEra(4);
        echo $a->getEra();
        $this->assertEquals(84, $a->getEra());
    }
}
