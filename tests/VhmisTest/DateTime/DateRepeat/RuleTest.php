<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Rule
     */
    protected $rule;

    public function setUp()
    {
        $this->rule = new Rule;
    }

    public function testReset()
    {
        $result = array(
            'by'          => 4,
            'base'        => null,
            'baseDay'     => null,
            'baseWeekday' => null,
            'baseMonth'   => null,
            'end'         => null,
            'times'       => 0,
            'freq'        => 1,
            'type'        => 'day',
            'days'        => array(),
            'weekdays'    => array(),
            'months'      => array(),
            'day'         => null,
            'position'    => null
        );

        $this->assertEquals($result, $this->rule->reset()->getInfo());
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetBaseDateException()
    {
        $this->rule->reset()->setBaseDate('2012-04-31');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetEndDateException()
    {
        $this->rule->reset()->setEndDate('2012-04-31');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatTimesException()
    {
        $this->rule->reset()->setRepeatTimes(-1);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetFrequencyException()
    {
        $this->rule->reset()->setFrequency(0);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDayException()
    {
        $this->rule->reset()->setRepeatedDay('-1');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDayException2()
    {
        $this->rule->reset()->setRepeatedDay(8);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDayPositionException()
    {
        $this->rule->reset()->setRepeatedDayPosition(-1);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDayPositionException2()
    {
        $this->rule->reset()->setRepeatedDayPosition(5);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException()
    {
        $this->rule->reset()->setRepeatWeekdays('2,7');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException2()
    {
        $this->rule->reset()->setRepeatWeekdays(array(-1, 4, 6));
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException3()
    {
        $this->rule->reset()->setRepeatWeekdays(5);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDaysException()
    {
        $this->rule->reset()->setRepeatedDays(array(0));
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedDaysException2()
    {
        $this->rule->reset()->setRepeatedDays(array(32));
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedMonthsException()
    {
        $this->rule->reset()->setRepeatedMonths(array(-1));
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetRepeatedMonthsException2()
    {
        $this->rule->reset()->setRepeatedMonths(array(13));
    }

    public function testSetRepeatedBy()
    {
        $result = array(
            'by'          => 4,
            'base'        => null,
            'baseDay'     => null,
            'baseWeekday' => null,
            'baseMonth'   => null,
            'end'         => null,
            'times'       => 0,
            'freq'        => 1,
            'type'        => 'day',
            'days'        => array(),
            'weekdays'    => array(),
            'months'      => array(),
            'day'         => null,
            'position'    => null
        );
        $this->rule->reset()->setRepeatByDay();
        $this->assertEquals($result, $this->rule->getInfo());

        $result = array(
            'by'          => 5,
            'base'        => null,
            'baseDay'     => null,
            'baseWeekday' => null,
            'baseMonth'   => null,
            'end'         => null,
            'times'       => 0,
            'freq'        => 1,
            'type'        => 'day',
            'days'        => array(),
            'weekdays'    => array(),
            'months'      => array(),
            'day'         => null,
            'position'    => null
        );
        $this->rule->reset()->setRepeatByWeek();
        $this->assertEquals($result, $this->rule->getInfo());

        $result = array(
            'by'          => 6,
            'base'        => null,
            'baseDay'     => null,
            'baseWeekday' => null,
            'baseMonth'   => null,
            'end'         => null,
            'times'       => 0,
            'freq'        => 1,
            'type'        => 'day',
            'days'        => array(),
            'weekdays'    => array(),
            'months'      => array(),
            'day'         => null,
            'position'    => null
        );
        $this->rule->reset()->setRepeatByMonth();
        $this->assertEquals($result, $this->rule->getInfo());

        $result = array(
            'by'          => 7,
            'base'        => null,
            'baseDay'     => null,
            'baseWeekday' => null,
            'baseMonth'   => null,
            'end'         => null,
            'times'       => 0,
            'freq'        => 1,
            'type'        => 'day',
            'days'        => array(),
            'weekdays'    => array(),
            'months'      => array(),
            'day'         => null,
            'position'    => null
        );
        $this->rule->reset()->setRepeatByYear();
        $this->assertEquals($result, $this->rule->getInfo());
    }

    public function testSet()
    {
        $value = array(
            'by'       => 4,
            'base'     => '2011-02-04',
            'end'      => '2011-05-07',
            'times'    => '7',
            'freq'     => '2',
            'type'     => 'day',
            'days'     => '4, 15, 6, 8',
            'weekdays' => '0, 5, 4, 3',
            'months'   => '11, 2, 4, 12',
            'day'      => '7',
            'position' => '4'
        );

        $result = array(
            'by'          => 4,
            'base'        => '2011-02-04',
            'baseDay'     => 4,
            'baseWeekday' => 5,
            'baseMonth'   => 2,
            'end'         => '2011-05-07',
            'times'       => 7,
            'freq'        => 2,
            'type'        => 'day',
            'days'        => array(4, 6, 8, 15),
            'weekdays'    => array(0, 3, 4, 5),
            'months'      => array(2, 4, 11, 12),
            'day'         => 7,
            'position'    => 4
        );

        $this->rule->reset()->setRepeatByDay()->setBaseDate($value['base'])->setEndDate($value['end'])
            ->setRepeatTimes($value['times'])->setFrequency($value['freq'])->setType($value['type'])
            ->setRepeatWeekdays($value['weekdays'])->setRepeatedDays($value['days'])->setRepeatedDay($value['day'])
            ->setRepeatedDayPosition($value['position'])->setRepeatedMonths($value['months']);

        $this->assertEquals($result, $this->rule->getInfo());
    }

    public function testIsNotValid()
    {
        $this->rule->reset();

        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatByDay();
        $this->assertEquals(false, $this->rule->isValid());
    }

    /**
     *
     */
    public function testValidRepeatByDaily()
    {
        $this->rule->reset();
        $this->rule->setRepeatByDay();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2014-05-30');
        $this->rule->setFrequency(1);

        $this->assertEquals(true, $this->rule->isValid());
    }

    /**
     *
     */
    public function testValidRepeatByWeek()
    {
        $this->rule->reset();
        $this->rule->setRepeatByWeek();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2014-06-01');
        $this->rule->setFrequency(1);
        $this->rule->setRepeatWeekdays(array(1, 5));
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatWeekdays(array(1, 5, 3));
        $this->assertEquals(true, $this->rule->isValid());
    }

    /**
     *
     */
    public function testValidRepeatByMonth()
    {
        $this->rule->reset();
        $this->rule->setRepeatByMonth();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2016-06-01');
        $this->rule->setFrequency(1);
        $this->rule->setType('day');
        $this->rule->setRepeatedDays(array(1, 4));
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDays(array(1, 4, 28));
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->reset();
        $this->rule->setRepeatByMonth();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2016-06-01');
        $this->rule->setFrequency(1);
        $this->rule->setType('relative_day');
        $this->rule->setRepeatedDay(2);
        $this->rule->setRepeatedDayPosition(4);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(2);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(4);
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(3);
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->setBaseDate('2014-05-21');

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(3);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(2);
        $this->assertEquals(true, $this->rule->isValid());
    }

    /**
     *
     */
    public function testValidRepeatByYear()
    {
        $this->rule->reset();
        $this->rule->setRepeatByYear();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2016-06-01');
        $this->rule->setFrequency(1);
        $this->rule->setRepeatedMonths(array(3, 4));
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setType('day');
        $this->rule->setRepeatedMonths(array(5));
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->reset();
        $this->rule->setRepeatByYear();
        $this->rule->setBaseDate('2014-05-28');
        $this->rule->setEndDate('2016-06-01');
        $this->rule->setFrequency(1);
        $this->rule->setRepeatedMonths(array(3, 4));
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedMonths(array(5));
        $this->rule->setType('relative_day');

        $this->rule->setRepeatedDay(2);
        $this->rule->setRepeatedDayPosition(4);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(2);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(4);
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(3);
        $this->assertEquals(true, $this->rule->isValid());

        $this->rule->setBaseDate('2014-05-21');

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(3);
        $this->assertEquals(false, $this->rule->isValid());

        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(2);
        $this->assertEquals(true, $this->rule->isValid());
    }
}
