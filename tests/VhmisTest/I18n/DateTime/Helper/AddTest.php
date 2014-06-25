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
use \Vhmis\I18n\DateTime\Helper\Add;

class AddTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Add object
     * @var Add
     */
    protected $add;

    /**
     * Date object
     *
     * @var DateTime
     */
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

        $this->add = new Add;
        $this->date = new DateTime;
        $this->add->setDate($this->date);
    }

    public function testAddMillisecond()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34);
        $this->date->setField(14, 321);
        $this->add->addMillisecond(31);
        $this->assertEquals(0, $this->date->getField(11));
        $this->assertEquals(12, $this->date->getField(12));
        $this->assertEquals(34, $this->date->getField(13));
        $this->assertEquals(352, $this->date->getField(14));
    }

    public function testAddSecond()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34);
        $this->add->addSecond(31);
        $this->assertEquals(0, $this->date->getField(11));
        $this->assertEquals(13, $this->date->getField(12));
        $this->assertEquals(5, $this->date->getField(13));
    }

    public function testAddMinute()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34);
        $this->add->addMinute(60);
        $this->assertEquals(1, $this->date->getField(11));
        $this->assertEquals(12, $this->date->getField(12));
        $this->assertEquals(34, $this->date->getField(13));
    }

    public function testAddHour()
    {
        $this->date->setDate(2014, 1, 31)->setTime(0, 12, 34);
        $this->add->addHour(25);
        $this->assertEquals(1, $this->date->getField(11));
        $this->assertEquals(12, $this->date->getField(12));
        $this->assertEquals(34, $this->date->getField(13));
    }

    public function testAddDay()
    {
        $this->date->setDate(2014, 1, 31);
        $this->add->addDay(31);
        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(3, $this->date->getField(2));
        $this->assertEquals(3, $this->date->getField(5));
    }

    public function testAddWeek()
    {
        $this->date->setDate(2014, 1, 31);
        $this->add->addWeek(2);
        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(2, $this->date->getField(2));
        $this->assertEquals(14, $this->date->getField(5));
    }

    public function testAddMonth()
    {
        $this->date->setDate(2014, 1, 31);
        $this->add->addMonth(1);
        $this->assertEquals(2014, $this->date->getField(1));
        $this->assertEquals(2, $this->date->getField(2));
        $this->assertEquals(28, $this->date->getField(5));
    }

    public function testAddYear()
    {
        $this->date->setDate(2012, 2, 29);
        $this->add->addYear(3);
        $this->assertEquals(2015, $this->date->getField(1));
        $this->assertEquals(2, $this->date->getField(2));
        $this->assertEquals(28, $this->date->getField(5));
    }

    public function testAddEra()
    {
        $a = new DateTime('Asia/Ho_Chi_Minh', 'chinese');
        $a->setField(0, 80);
        $this->add->setDate($a);
        $this->add->addEra(4);
        $this->assertEquals(84, $a->getField(0));
    }
}
