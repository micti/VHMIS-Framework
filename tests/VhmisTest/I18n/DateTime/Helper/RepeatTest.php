<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\Helper;

use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\DateTime\Helper\Repeat;

class RepeatTest extends \PHPUnit_Framework_TestCase
{
    protected $repeat;

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
        
        $this->repeat = new Repeat;
    }

    public function testRepeatByDay()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);

        $this->repeat->setDateTimeObject($date);

        $this->assertEquals(array(
            '2015-01-02'
        ), $this->repeat->repeatByDay('2015-01-02', '2015-01-03', 5, 2));
    }

    public function testRepeatByWeek()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $date->setWeekFirstDay(2);

        $this->repeat->setDateTimeObject($date);

        $this->assertEquals(array(
            '2015-01-07'
        ), $this->repeat->repeatByWeek('2015-01-01', '2015-01-07'));
    }

    public function testRepeatByMonth()
    {
        $date = new DateTime();
        $date->setDate(2014, 12, 31);
        $date->setTime(23, 15, 54);
        $date->setWeekFirstDay(2);

        $this->repeat->setDateTimeObject($date);

        $this->assertEquals(array(
            '2015-01-31',
            '2015-03-03'
        ), $this->repeat->repeatByMonth('2015-01-01', '2015-03-29'));
    }

    public function testRepeatByYear()
    {
        $date = new DateTime();
        $date->setDate(2014, 01, 01);
        $date->setTime(23, 15, 54);
        $date->setWeekFirstDay(2);

        $this->repeat->setDateTimeObject($date);

        $this->assertEquals(array(
            '2014-01-01',
            '2015-01-01',
            '2016-01-01',
            '2017-01-01'
        ), $this->repeat->repeatByYear('2010-01-01', '2017-12-29'));
    }
}