<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Month;
use Vhmis\DateTime\DateRepeat\Rule;

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
     * Setup
     */
    public function setUp()
    {
        $this->monthRepeat = new Month('2014-05-12', null, 0, 1);
        $this->repeatRule = new Rule();
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setBaseDate('2013-01-01');
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2013-01-01');
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-17')->setEndDate('2014-07-09');
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-09', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-17')
            ->setType('day')->setRepeatedDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-09-12', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-12')
            ->setType('day')->setRepeatedDays(array(12, 17, 20))
            ->setRepeatTimes(6)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-20', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-01-29')
            ->setType('day')->setRepeatedDays(array(29, 30, 31))
            ->setRepeatTimes(6)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-03-03', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-01-31')
            ->setType('relative_day')->setRepeatedDay(7)->setRepeatedDayPosition(4)
            ->setRepeatTimes(7)->setFrequency(1);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-31', $this->monthRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-24')
            ->setType('relative_day')
            ->setRepeatTimes(2)->setFrequency(2);
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals('2014-07-26', $this->monthRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->monthRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->monthRepeat->repeatedDates('2013-01-01', '2013-02-01'));
        
        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-12')
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

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-31')
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

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-02-28')
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

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-01-31')
            ->setType('relative_day')->setRepeatedDayPosition(4)->setRepeatedDay(7)
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

        $this->repeatRule->reset()->setRepeatByMonth()->setBaseDate('2014-05-24')
            ->setType('relative_day')->setRepeatedDayPosition(3)->setRepeatedDay(6)
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
