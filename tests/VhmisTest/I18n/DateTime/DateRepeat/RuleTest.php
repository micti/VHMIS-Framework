<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\DateRepeat;

use \Vhmis\I18n\DateTime\DateTime;
use \Vhmis\I18n\DateTime\DateRepeat\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    protected $rule;

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

        $this->rule = new Rule;
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetByException()
    {
        $this->rule->setRepeatBy(3);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetByException2()
    {
        $this->rule->setRepeatBy(8);
    }

    public function testSetBy()
    {
        $this->rule->setRepeatBy(7);
        $info = $this->rule->getInfo();

        $this->assertEquals(7, $info['by']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetBaseDateException()
    {
        $this->rule->setBaseDate(3);
    }

    public function testSetBaseDate()
    {
        $date = new DateTime('Asia/Ho_Chi_Minh');
        $date->setDate(2014, 7, 22);

        $this->rule->setBaseDate($date);
        $info = $this->rule->getInfo();

        $this->assertEquals('2014-07-22', $info['base']);
        $this->assertEquals(22, $info['baseDay']);
        $this->assertEquals(3, $info['baseWeekday']);
        $this->assertEquals(7, $info['baseMonth']);
        $this->assertEquals(array(22), $info['days']);
        $this->assertEquals(array(3), $info['weekdays']);
        $this->assertEquals(array(7), $info['months']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetEndDateException()
    {
        $this->rule->setEndDate('fdfd');
    }

    public function testSetEndDate()
    {
        $this->rule->setEndDate('2123-12-12');

        $info = $this->rule->getInfo();

        $this->assertEquals('2123-12-12', $info['end']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatTimesException()
    {
        $this->rule->setRepeatTimes(-4);
    }

    public function testSetRepeatTimes()
    {
        $this->rule->setRepeatTimes(8);

        $info = $this->rule->getInfo();

        $this->assertEquals(8, $info['times']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetFrequencyException()
    {
        $this->rule->setFrequency(0);
    }

    public function testSetFrequency()
    {
        $this->rule->setFrequency(3);

        $info = $this->rule->getInfo();

        $this->assertEquals(3, $info['freq']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException()
    {
        $this->rule->setRepeatWeekdays(2);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException2()
    {
        $this->rule->setRepeatWeekdays('a');
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatWeekdaysException3()
    {
        $this->rule->setRepeatWeekdays('0,1,2,3,4');
    }

    public function testSetRepeatWeekdays()
    {
        $this->rule->setRepeatWeekdays(array(3,5,6,6));

        $info = $this->rule->getInfo();

        $this->assertEquals(array(3,5,6), $info['weekdays']);
    }

    public function testSetTypeDay()
    {
        $this->rule->setType('day');

        $info = $this->rule->getInfo();

        $this->assertEquals('day', $info['type']);
    }

    public function testSetTypeRelativeDay()
    {
        $this->rule->setType('relative_day');

        $info = $this->rule->getInfo();

        $this->assertEquals('relative_day', $info['type']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatDayException()
    {
        $this->rule->setRepeatedDay('-1');
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatDayException2()
    {
        $this->rule->setRepeatedDay(10);
    }

    public function testSetRepeatDay()
    {
        $this->rule->setRepeatedDay(2);

        $info = $this->rule->getInfo();

        $this->assertEquals(2, $info['day']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedDayPositionException()
    {
        $this->rule->setRepeatedDayPosition('11');
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedDayPositionException2()
    {
        $this->rule->setRepeatedDayPosition(-11);
    }

    public function testSetRepeatedDayPosition()
    {
        $this->rule->setRepeatedDayPosition('2');

        $info = $this->rule->getInfo();

        $this->assertEquals(2, $info['position']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedDaysException()
    {
        $this->rule->setRepeatedDays('33');
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedDaysException2()
    {
        $this->rule->setRepeatedDays(array(2,-1));
    }

    public function testSetRepeatedDays()
    {
        $this->rule->setRepeatedDays('2,4,5,19,12');

        $info = $this->rule->getInfo();

        $this->assertEquals(array(2,4,5,12,19), $info['days']);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedMonthException()
    {
        $this->rule->setRepeatedMonths(array(14));
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testSetRepeatedMonthsException2()
    {
        $this->rule->setRepeatedMonths(array(5,7,-11));
    }

    public function testSetRepeatedMonths()
    {
        $this->rule->setRepeatedDays('2,4,5,19,12');

        $info = $this->rule->getInfo();

        $this->assertEquals(array(2,4,5,12,19), $info['days']);
    }

    public function testReset()
    {
        $result = array(
            'date'        => null,
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

    public function testIsValidNotBaseDate()
    {
        $this->rule->reset();

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForDay()
    {
        $this->rule->reset();

        $date = new DateTime;
        $date->setDateWithExtenedYear(2014, 7, 22);

        $this->rule->setBaseDate($date);

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function testIsValidForWeek()
    {
        $this->rule->reset();

        $date = new DateTime;
        $date->setDateWithExtenedYear(2014, 7, 22);

        $this->rule->setBaseDate($date);
        $this->rule->setRepeatBy(5);
        $this->rule->setRepeatWeekdays('4,5');

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForWeek2()
    {
        $this->rule->reset();

        $date = new DateTime;
        $date->setDateWithExtenedYear(2014, 7, 22);

        $this->rule->setBaseDate($date);
        $this->rule->setRepeatBy(5);
        $this->rule->setRepeatWeekdays('3,5');

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function testIsValidForMonth2()
    {
        $this->preTest(6);

        $this->rule->setType('day');
        $this->rule->setRepeatedDays(array(12));

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForMonth3()
    {
        $this->preTest(6);

        $this->rule->setType('day');
        $this->rule->setRepeatedDays(array(12,22));

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function testIsValidForMonth4()
    {
        $this->preTest(6);

        $this->rule->setType('relative_day');
        $this->rule->setRepeatedDay(2);
        $this->rule->setRepeatedDayPosition(3);

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForMonth5()
    {
        $this->preTest(6);

        $this->rule->setType('relative_day');
        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(4);

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function testIsValidForYear()
    {
        $this->preTest(7);

        $this->rule->setRepeatedMonths(array(1));

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForYear2()
    {
        $this->preTest(7);

        $this->rule->setRepeatedMonths(array(7,4));
        $this->rule->setType('day');

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function testIsValidForYear3()
    {
        $this->preTest(7);

        $this->rule->setRepeatedMonths(array(7,4));
        $this->rule->setType('relative_day');
        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(5);

        $this->assertEquals(false, $this->rule->isValid());
    }

    public function testIsValidForYear4()
    {
        $this->preTest(7);

        $this->rule->setRepeatedMonths(array(7,4));
        $this->rule->setType('relative_day');
        $this->rule->setRepeatedDay(3);
        $this->rule->setRepeatedDayPosition(4);

        $this->assertEquals(true, $this->rule->isValid());
    }

    public function preTest($by)
    {
        $this->rule->reset();

        $date = new DateTime;
        $date->setDateWithExtenedYear(2014, 7, 22);

        $this->rule->setBaseDate($date);
        $this->rule->setRepeatBy($by);
    }
}
