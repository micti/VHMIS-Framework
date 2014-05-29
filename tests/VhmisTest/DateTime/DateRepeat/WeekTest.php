<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Week;
use Vhmis\DateTime\DateRepeat\Rule;

class WeekTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Week Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Week
     */
    protected $weekRepeat;

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
        $this->weekRepeat = new Week('2011-01-01', null, 0, 1);
        $this->repeatRule = new Rule();
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setBaseDate('2013-01-01');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2013-01-01');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-31-21', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2013-01-01')->setEndDate('2013-02-02');
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-02-02', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-03')->setRepeatTimes(6)->setFrequency(1)
            ->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
        $this->assertEquals('2014-05-11', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-03')->setRepeatTimes(6)->setFrequency(3)
            ->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
        $this->assertEquals('2014-05-25', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-03')->setRepeatTimes(4)->setFrequency(1)
            ->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
        $this->assertEquals('2014-05-06', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-03')->setRepeatTimes(8)->setFrequency(2)
            ->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
        $this->assertEquals('2014-05-20', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-05')->setRepeatTimes(7)->setFrequency(1)
            ->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek(5)->setRule($this->repeatRule);
        $this->assertEquals('2014-05-17', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-05')->setRepeatTimes(2)->setFrequency(2)
            ->setRepeatWeekdays('1');
        $this->weekRepeat->setStartDayOfWeek(5)->setRule($this->repeatRule);
        $this->assertEquals('2014-05-19', $this->weekRepeat->endDate());

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-05')->setRepeatTimes(2)->setFrequency(2)
            ->setRepeatWeekdays('1, 0');
        $this->weekRepeat->setStartDayOfWeek(5)->setRule($this->repeatRule);
        $this->assertEquals('2014-05-18', $this->weekRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->weekRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->weekRepeat->repeatedDates('2013-01-01', '2013-02-01'));
        
        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-03')->setEndDate('2014-05-11')
            ->setFrequency(1)->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
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

        $this->repeatRule->reset()->setRepeatByWeek()->setBaseDate('2014-05-04')->setEndDate('2014-05-11')
            ->setFrequency(1)->setRepeatWeekdays('1, 2, 6, 0');
        $this->weekRepeat->setStartDayOfWeek('friday')->setRule($this->repeatRule);
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
