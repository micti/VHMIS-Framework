<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Year;
use Vhmis\DateTime\DateRepeat\Rule;

class YearTest extends \PHPUnit_Framework_TestCase
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
     * Setup
     */
    public function setUp()
    {
        $this->yearRepeat = new Year('2014-05-12', null, 0, 1);
        $this->repeatRule = new Rule();
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setBaseDate('2013-01-01');
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2013-01-01');
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-17')->setEndDate('2014-07-09');
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-09', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-17')
            ->setType('day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2018-01-17', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-17')
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-16', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-10')
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-09', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-03')
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-01-02', $this->yearRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-31')
            ->setType('relative_day')->setRepeatedMonths(array(2, 5, 12))
            ->setRepeatedDay(7)->setRepeatedDayPosition(4)
            ->setRepeatTimes(6)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $this->assertEquals('2016-02-29', $this->yearRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-12')
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

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-05-31')
            ->setType('day')->setRepeatedMonths(array(5, 2))
            ->setRepeatTimes(3)->setFrequency(1);
        $this->yearRepeat->setRule($this->repeatRule);
        $result = array(
            '2014-05-31',
            '2015-03-03',
            '2015-05-31'
        );
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-04-20', '2015-06-12'));

        $this->repeatRule->reset()->setRepeatByYear()->setBaseDate('2014-02-28')
            ->setType('relative_day')->setRepeatedMonths(array(2))
            ->setRepeatedDayPosition(4)->setRepeatedDay(7)
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
