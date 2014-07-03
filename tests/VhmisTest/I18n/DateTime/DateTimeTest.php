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

    public function testGetMaximum()
    {
        $a = \IntlCalendar::createInstance('Asia/Ho_Chi_Minh', 'vi_VN');
        $a->set(1970, 0, 1, 7, 0, 0);

        $result = array(
            'actual'   => $a->getActualMaximum(0),
            'least'  => $a->getLeastMaximum(0),
            'greatest' => $a->getMaximum(0)
        );

        $this->date->setDate(1970, 1, 1)->setTime(7, 0, 0);
        $this->assertEquals($result, $this->date->getMaximumValueOfField(0));
    }

    public function testGetMinimum()
    {
        $a = \IntlCalendar::createInstance('Asia/Ho_Chi_Minh', 'vi_VN');
        $a->set(1970, 0, 1, 7, 0, 0);

        $result = array(
            'actual'   => $a->getActualMinimum(0),
            'least'  => $a->getMinimum(0),
            'greatest' => $a->getGreatestMinimum(0)
        );

        $this->date->setDate(1970, 1, 1)->setTime(7, 0, 0);
        $this->assertEquals($result, $this->date->getMinimumValueOfField(0));
    }

    public function testGetDayOfWeekType()
    {
        $a = new DateTime(null, null, 'vi_VN');

        $result = array(
            '1' => array(1, 86400000),
            '2' => array(0),
            '3' => array(0),
            '4' => array(0),
            '5' => array(0),
            '6' => array(0),
            '7' => array(1, 0),
        );

        $this->assertEquals($result, $this->date->getDayOfWeekType());
    }

    public function testCallWrong()
    {
        $this->assertEquals(null, $this->date->nothing());
    }

    public function testCall()
    {
        $this->assertEquals($this->date, $this->date->setDay(1));
    }

    public function testGetHelper()
    {
        $this->assertInstanceOf('\Vhmis\I18n\DateTime\Helper\Get', $this->date->getHelper('get'));
    }

    public function testGetHelperWrong()
    {
        $this->assertEquals(null, $this->date->getHelper('wrong'));
    }

    public function testGet()
    {
        $this->assertEquals($this->date->getHelper('get'), $this->date->get);
    }

    public function testGetWrong()
    {
        $this->assertEquals(null, $this->date->wrong);
    }
}
