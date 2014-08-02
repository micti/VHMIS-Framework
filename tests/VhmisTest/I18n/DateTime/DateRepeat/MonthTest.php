<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\DateRepeat;

use Vhmis\I18n\DateTime\DateRepeat\Month;
use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\DateTime\DateRepeat\Rule;

class MonthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Month Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Month
     */
    protected $monthRepeat;

    /**
     * Rule object
     *
     * @var Vhmis\DateTime\DateRepeat\Rule
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

        $this->monthRepeat = new Month();
        $this->repeatRule = new Rule();
        $this->date = new DateTime('Asia/Ho_Chi_Minh');
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());
    }

    public function testEndDate2()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(5);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());
    }

    public function testEndDate3()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());
    }

    public function testEndDate4()
    {
        $this->date->modify('2014-05-17');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)->setEndDate('2014-07-09');
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-09', $this->monthRepeat->endDate());
    }

    public function testEndDate5()
    {
        $this->date->modify('2014-05-17');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-09-12', $this->monthRepeat->endDate());
    }

    public function testEndDate6()
    {
        $this->date->modify('2014-05-12');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-20', $this->monthRepeat->endDate());
    }

    public function testEndDate7()
    {
        $this->date->modify('2014-01-29');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(29, 30, 31))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-03-03', $this->monthRepeat->endDate());
    }

    public function testEndDate8()
    {
        $this->date->modify('2014-01-31');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('relative_day')->setRepeatedDay(0)->setRepeatedDayPosition(-1)
            ->setRepeatTimes(7)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-31', $this->monthRepeat->endDate());
    }

    public function testEndDate9()
    {
        $this->date->modify('2014-05-24');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('relative_day')
            ->setRepeatTimes(2)->setFrequency(2);
        var_dump($this->repeatRule->getInfo());
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-26', $this->monthRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2013-01-01', '2013-02-01'));
    }

    public function testRepeatedDates2()
    {
        $this->date->modify('2014-05-12');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);

        $result = array(
            '2014-05-12',
            '2014-05-17',
            '2014-05-20',
            '2014-06-12',
            '2014-06-17',
            '2014-06-20',
        );

        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-12', '2014-06-20'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-12', '2014-09-12'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-06-20'));
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-09-12'));
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2013-04-20', '2014-01-12'));
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2015-04-20', '2016-09-12'));

        $result = array(
            '2014-05-20',
            '2014-06-12'
        );

        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-18', '2014-06-16'));
    }

    public function testRepeatedDates3()
    {
        $this->date->modify('2014-05-31');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(29, 30, 31))
            ->setRepeatTimes(7)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-31',
            '2014-06-29',
            '2014-06-30',
            '2014-07-01',
            '2014-07-29',
            '2014-07-30',
            '2014-07-31'
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-04-20', '2014-09-12'));
    }

    public function testRepeatedDates4()
    {
        $this->date->modify('2014-02-28');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('day')->setRepeatedDays(array(1, 28, 29))
            ->setRepeatTimes(9)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-02-28',
            '2014-03-01',
            '2014-03-02',
            '2014-03-28',
            '2014-03-29',
            '2014-04-01',
            '2014-04-28',
            '2014-04-29',
            '2014-05-01',
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-02-20', '2014-09-12'));
    }

    public function testRepeatedDates5()
    {
        $this->date->modify('2014-01-31');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('relative_day')->setRepeatedDayPosition(-1)->setRepeatedDay(0)
            ->setRepeatTimes(7)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-01-31',
            '2014-02-28',
            '2014-03-31',
            '2014-04-30',
            '2014-05-31',
            '2014-06-30',
            '2014-07-31',
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-01-31', '2014-07-31'));
    }

    public function testRepeatedDates6()
    {
        $this->date->modify('2014-05-24');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(6)
            ->setType('relative_day')->setRepeatedDayPosition(4)->setRepeatedDay(7)
            ->setRepeatTimes(2)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-24'
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-05-24', '2014-05-24'));

        $result = array(
            '2014-07-26'
        );
        $this->assertEquals($result, $this->monthRepeat->repeatedDates('2014-06-23', '2014-07-28'));
    }
}
