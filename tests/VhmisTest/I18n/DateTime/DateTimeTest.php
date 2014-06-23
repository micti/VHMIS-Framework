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

        $this->date = new DateTime('Asia/Saigon', '', 'vi_VN');
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

    public function testFormat()
    {
        $this->date->setDate(2012, 1, 29);
        $this->date->setTime(11, 11, 11);

        $this->assertEquals('11:11:11 Giờ Đông Dương Chủ Nhật, ngày 29 tháng 1 năm 2012', $this->date->format(0));
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

    public function testSetTimeZone()
    {
        $this->date->setTimeZone('Asia/Tokyo');
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->assertEquals('2014-06-24 14:12:13', $this->date->getDateTime());
        $this->date->setTimeZone('Asia/Ho_Chi_Minh');
        $this->assertEquals('2014-06-24 12:12:13', $this->date->getDateTime());
    }

    public function testSetTimestamp()
    {
        $this->date->setTimestamp(0);
        $this->assertEquals('1970-01-01 07:00:00', $this->date->getDateTime());
    }

    public function testGetTimestamp()
    {
        $a = \IntlCalendar::createInstance('Asia/Ho_Chi_Minh', 'vi_VN');
        $a->set(1970, 0, 1, 7, 0, 0);

        $this->date->setDate(1970, 1, 1)->setTime(7, 0, 0);
        $this->assertEquals((int) ($a->getTime() / 1000), $this->date->getTimestamp());
    }

    public function testGetTimeZone()
    {
        $this->date->setTimeZone('Asia/Ho_Chi_Minh');
        $this->assertEquals('Indochina Time', $this->date->getTimeZone());
    }

    public function testGetType()
    {
        $a = new DateTime(null, 'japanese');
        $this->assertEquals('japanese', $a->getType());

        $a = new DateTime(null, 'taiwan');
        $this->assertEquals('gregorian', $a->getType());
    }

    public function testToString()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->assertEquals('2014-06-24 14:12:13', (string) $this->date);
    }

    public function testCallWrong()
    {
        $this->assertEquals(null, $this->date->nothing());
    }

    public function testCall()
    {
        $this->assertEquals($this->date, $this->date->setDay(1));
    }
}
