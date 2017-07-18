<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\DateRepeat;

use Vhmis\I18n\DateTime\DateRepeat\Year;
use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\DateTime\DateRepeat\Rule;

class YearTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Year Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Year
     */
    protected $yearRepeat;

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

        $this->yearRepeat = new Year;
        $this->repeatRule = new Rule;
        $this->date = new DateTime('Asia/Ho_Chi_Minh');
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->yearRepeat->endDate());
    }

    public function testEndDate2()
    {
        $this->date->modify('2013-01-01');
        $this->repeatRule->reset()->setBaseDate($this->date);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->yearRepeat->endDate());
    }

    public function testEndDate3()
    {
        $this->date->modify('2013-01-01');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->yearRepeat->endDate());
    }

    public function testEndDate4()
    {
        $this->date->modify('2014-05-17');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)->setEndDate('2014-07-09');
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-09', $this->yearRepeat->endDate());
    }

    public function testEndDate5()
    {
        $this->date->modify('2014-05-17');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2018-01-17', $this->yearRepeat->endDate());
    }

    public function testEndDate7()
    {
        $this->date->modify('2014-05-17');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-16', $this->yearRepeat->endDate());
    }

    public function testEndDate8()
    {
        $this->date->modify('2014-05-10');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-09', $this->yearRepeat->endDate());
    }

    public function testEndDate9()
    {
        $this->date->modify('2014-05-03');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-02', $this->yearRepeat->endDate());
    }

    public function testEndDate10()
    {
        $this->date->modify('2014-05-31');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('relative_day')->setRepeatedMonths(array(2, 5, 12))
            ->setRepeatedDay(0)->setRepeatedDayPosition(-1)
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-02-29', $this->yearRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->yearRepeat->repeatedDates('2013-01-01', '2013-02-01'));
    }

    public function testRepeatedDates2()
    {
        $this->date->modify('2014-05-12');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-12',
            '2014-12-12',
            '2015-01-12',
            '2015-05-12',
            '2015-12-12',
            '2016-01-12',
        );

        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-05-12', '2016-01-12'));
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-05-12', '2016-09-12'));
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-04-20', '2016-01-12'));
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-04-20', '2016-09-12'));
        $this->assertEquals(array(), $this->yearRepeat->repeatedDates('2013-04-20', '2014-01-12'));
        $this->assertEquals(array(), $this->yearRepeat->repeatedDates('2016-04-20', '2016-09-12'));

        $result = array(
            '2014-05-12'
        );

        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-05-12', '2014-05-12'));
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-05-12', '2014-12-11'));
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-05-11', '2014-05-19'));

        $result = array(
            '2015-12-12'
        );

        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2015-12-11', '2015-12-19'));
    }

    public function testRepeatedDates3()
    {
        $this->date->modify('2014-05-31');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('day')->setRepeatedMonths(array(5, 2))
            ->setRepeatTimes(3)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-31',
            '2015-03-03',
            '2015-05-31'
        );
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-04-20', '2015-06-12'));
    }

    public function testRepeatedDates4()
    {
        $this->date->modify('2014-02-28');
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(7)
            ->setType('relative_day')->setRepeatedMonths(array(2))
            ->setRepeatedDayPosition(-1)->setRepeatedDay(0)
            ->setRepeatTimes(4)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-02-28',
            '2015-02-28',
            '2016-02-29',
            '2017-02-28'
        );
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-02-20', '2017-09-12'));
    }
}
