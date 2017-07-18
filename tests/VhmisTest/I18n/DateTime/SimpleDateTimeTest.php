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

class SimpleDateTimeTest extends \PHPUnit\Framework\TestCase
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

        $this->date = new DateTime('Asia/Ho_Chi_Minh', '', 'vi_VN');
        //$this->date->setTimeZone($timeZone)
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

    public function testGetField()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);

        $this->assertEquals(2014, $this->date->getField(1));
    }

    public function testGetMonthField()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);

        $this->assertEquals(6, $this->date->getField(2));
    }

    public function testSetField()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->date->setField(1, 2015);

        $this->assertEquals(2015, $this->date->getField(1));
    }

    public function testSetMonthField()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->date->setField(2, 4);

        $this->assertEquals(4, $this->date->getField(2));
    }

    public function testSetFieldGreaterMaxValue()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);

        $this->assertEquals(false, $this->date->setField(1, 1456572015));
    }

    public function testSetFieldSmallerMinValue()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);

        $this->assertEquals(false, $this->date->setField(1, 0));
    }

    public function testAddField()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->date->addField(1, 1);

        $this->assertEquals(2015, $this->date->getField(1));
    }

    public function testSetAndGetDate()
    {
        $this->date->setDate(1, 1, 1);
        $this->assertEquals(1, $this->date->getField(1));
        $this->assertEquals(1, $this->date->getField(2));
        $this->assertEquals(1, $this->date->getField(5));

        $this->date->setDate(2014, 2, 28);
        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(2, $this->date->getField(2));
        $this->assertEquals(28, $this->date->getField(5));

        $this->date->setDate(2014, 0, 28);
        $this->assertEquals(2013, $this->date->getField(1));
        $this->assertEquals(12, $this->date->getField(2));
        $this->assertEquals(28, $this->date->getField(5));

        $this->date->setDate(2014, -1, 28);
        $this->assertEquals(2013, $this->date->getField(1));
        $this->assertEquals(11, $this->date->getField(2));
        $this->assertEquals(28, $this->date->getField(5));
    }

    public function testSetAndGetTime()
    {
        $this->date->setTime(1, 1, 1);
        $this->assertEquals(1, $this->date->getField(11));
        $this->assertEquals(1, $this->date->getField(12));
        $this->assertEquals(1, $this->date->getField(13));

        $this->date->setTime(0, 0, 0);
        $this->assertEquals(0, $this->date->getField(11));
        $this->assertEquals(0, $this->date->getField(12));
        $this->assertEquals(0, $this->date->getField(13));

        $this->date->setTime(-1, -1, -1);
        $this->assertEquals('22:58:59', $this->date->getTime());
    }

    public function testSetDateWithExtendedYear()
    {
        $a = new DateTime(null, 'chinese');
        $a->setDateWithExtenedYear(4503, 5, 6);

        $this->assertEquals(4503, $a->getField(19));
        $this->assertEquals(5, $a->getField(2));
        $this->assertEquals(6, $a->getField(5));
    }

    public function testGetDateTime()
    {
        $this->date->setDate(11111, 2, 1); // move to 11111-02-01
        $this->date->setTime(-1, -1, -1); // move back 11111-01-31

        $this->assertEquals(11111, $this->date->getField(1));
        $this->assertEquals(1, $this->date->getField(2));
        $this->assertEquals(31, $this->date->getField(5));
        $this->assertEquals(22, $this->date->getField(11));
        $this->assertEquals(58, $this->date->getField(12));
        $this->assertEquals(59, $this->date->getField(13));

        $this->date->setDate(2014, 6, 6);
        $this->date->setTime(8, 8, 8);

        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(6, $this->date->getField(2));
        $this->assertEquals(6, $this->date->getField(5));
        $this->assertEquals(8, $this->date->getField(11));
        $this->assertEquals(8, $this->date->getField(12));
        $this->assertEquals(8, $this->date->getField(13));
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

        $this->assertEquals('11:11, 29/01/2012', $this->date->format(3));
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
        
        // Saigon in 1970 used GMT+8
        //$this->date->setTimeZone('Asia/Ho_Chi_Minh');
        //$this->assertEquals('1970-01-01 08:00:00', $this->date->getDateTime());
        
        $this->date->setTimeZone('Asia/Tokyo');
        $this->assertEquals('1970-01-01 09:00:00', $this->date->getDateTime());
    }

    public function testGetTimestamp()
    {
        $a = \IntlCalendar::createInstance('Asia/Tokyo', 'vi_VN');
        $a->set(1970, 0, 1, 7, 0, 0);
        $a->set(14, 0);

        $this->date->setTimeZone('Asia/Tokyo');
        $this->date->setDate(1970, 1, 1)->setTime(7, 0, 0, 0);
        $this->assertEquals((int) ($a->getTime() / 1000), $this->date->getTimestamp());
    }

    public function testSetMilliTimesptamp()
    {
        $this->date->setTimeZone('Asia/Ho_Chi_Minh');
        $this->date->setMilliTimestamp(strtotime('2014-06-27 00:30:00 GMT+07:00') * 1000);

        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(6, $this->date->getField(2));
        $this->assertEquals(27, $this->date->getField(5));
        $this->assertEquals(0, $this->date->getField(11));
        $this->assertEquals(30, $this->date->getField(12));
        $this->assertEquals(0, $this->date->getField(13));
    }

    public function testGetMilliTimesptamp()
    {
        $this->date->setTimestamp(3546565766);
        $this->date->setField(14, 948);

        $this->assertEquals(3546565766948, $this->date->getMilliTimestamp());
    }

    public function testDiff()
    {
        $a = new DateTime();
        $a->setDate(2014, 2, 28)->setTime(12, 12, 12)->setField(14, 000);
        $b = new DateTime();
        $b->setDate(2013, 2, 28)->setTime(12, 12, 12)->setField(14, 000);

        $this->assertEquals(-1, $a->diffField($b, 1));
        $this->assertEquals(0, $a->diffField($b, 2));
        $this->assertEquals(0, $a->diffField($b, 5));
        $this->assertEquals(0, $a->diffField($b, 11));
        $this->assertEquals(0, $a->diffField($b, 12));
        $this->assertEquals(0, $a->diffField($b, 13));
        $this->assertEquals(0, $a->diffField($b, 14));
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

    public function testGetFirstDayOfWeek()
    {
        $a = \IntlCalendar::createInstance('Asia/Ho_Chi_Minh', 'vi_VN');

        $this->assertEquals($a->getFirstDayOfWeek(), $this->date->getWeekFirstDay());
    }

    public function testSetFirstDayOfWeek()
    {
        $this->date->setWeekFirstDay(4);

        $this->assertEquals(4, $this->date->getWeekFirstDay());
    }

    public function testGetSortedWeekday()
    {
        $this->date->setWeekFirstDay(4);
        $this->assertEquals(array(4,5,6,7,1,2,3), $this->date->getSortedWeekday());
    }

    public function testToString()
    {
        $this->date->setDate(2014, 6, 24)->setTime(14, 12, 13);
        $this->assertEquals('2014-06-24 14:12:13', (string) $this->date);
    }

    public function testCreateNewWithSameI18nInfo()
    {
        $date = $this->date->createNewWithSameI18nInfo();
        $this->assertEquals($this->date->getType(), $date->getType());
        $this->assertEquals($this->date->getTimeZone(), $date->getTimeZone());
        $date->setMilliTimestamp($this->date->getMilliTimestamp());
        $this->assertEquals($this->date->format(array(0, 0)), $date->format(array(0, 0)));
    }
}
