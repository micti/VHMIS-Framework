<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\DateRepeat;

use Vhmis\I18n\DateTime\DateRepeat\Week;
use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\DateTime\DateRepeat\Rule;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Week Repeat object
     *
     * @var Week
     */
    protected $weekRepeat;

    /**
     * Rule object
     *
     * @var Rule
     */
    protected $repeatRule;

    /**
     * Datetime object
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Setup
     */
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

        $this->weekRepeat = new Week('2011-01-01', null, 0, 1);
        $this->repeatRule = new Rule();
        $this->date = new DateTime('Asia/Ho_Chi_Minh');
        $this->date->modify('2013-01-01');
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->weekRepeat->endDate());
    }

    public function testEndDate1()
    {
        $this->repeatRule->reset()->setBaseDate($this->date);
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->weekRepeat->endDate());
    }

    public function testEndDate2()
    {
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date);
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->weekRepeat->endDate());
    }

    public function testEndDate3()
    {

        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setEndDate('2013-02-02');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-02-02', $this->weekRepeat->endDate());
    }

    public function testEndDate4()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(6)->setFrequency(1)
            ->setRepeatWeekdays('2, 3, 7');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-13', $this->weekRepeat->endDate());
    }

    public function testEndDate5()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(6)->setFrequency(3)
            ->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-25', $this->weekRepeat->endDate());
    }

    public function testEndDate6()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(4)->setFrequency(1)
            ->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-06', $this->weekRepeat->endDate());
    }

    public function testEndDate7()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(8)->setFrequency(2)
            ->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-20', $this->weekRepeat->endDate());
    }

    public function testEndDate8()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(7)->setFrequency(1)
            ->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-12', $this->weekRepeat->endDate());
    }

    public function testEndDate9()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(2)->setFrequency(2)
            ->setRepeatWeekdays('7');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-17', $this->weekRepeat->endDate());
    }

    public function testEndDate10()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(1);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setRepeatTimes(2)->setFrequency(2)
            ->setRepeatWeekdays('7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-05-11', $this->weekRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2013-01-01', '2013-02-01'));
    }

    public function testRepeatedDates1()
    {
        $this->date->modify('2014-05-03')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setEndDate('2014-05-11')
            ->setFrequency(1)->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-03',
            '2014-05-04',
            '2014-05-05',
            '2014-05-06',
            '2014-05-10',
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-11'));

        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2014-05-01', '2014-05-02'));
        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2014-05-12', '2017-05-03'));

        $result = array(
            '2014-05-03',
            '2014-05-04',
            '2014-05-05',
            '2014-05-06'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-09'));
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-03', '2014-05-06'));

        $result = array(
            '2014-05-04',
            '2014-05-05'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-04', '2014-05-05'));
    }

    public function testRepeatedDates2()
    {
        $this->date->modify('2014-05-04')->setWeekFirstDay(5);
        $this->repeatRule->reset()->setRepeatBy(5)->setBaseDate($this->date)->setEndDate('2014-05-11')
            ->setFrequency(1)->setRepeatWeekdays('2, 3, 7, 1');
        $this->weekRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-10',
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-09', '2014-05-14'));

        $result = array(
            '2014-05-11'
        );
        $this->assertEquals($result, $this->weekRepeat->repeatedDates('2014-05-11', '2014-05-11'));
    }
}
