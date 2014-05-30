<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Day;
use Vhmis\DateTime\DateRepeat\Rule;

class DayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Day Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Day
     */
    protected $dayRepeat;

    /**
     * Rule object
     *
     * @var Vhmis\DateTime\DateRepeat\Rule
     */
    protected $repeatRule;

    public function setUp()
    {
        $this->dayRepeat = new Day('2011-01-01', null, 0, 1);
        $this->repeatRule = new Rule();
    }

    public function testEndDate()
    {
        $this->repeatRule->reset();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());

        $this->repeatRule->reset()->setBaseDate('2013-01-01')->setRepeatByMonth();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());

        $this->repeatRule->reset()->setBaseDate('2013-01-01');
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());

        $this->repeatRule->setEndDate('2013-02-02');
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-02-02', $this->dayRepeat->endDate());

        $this->repeatRule->reset();
        $this->repeatRule->setBaseDate('2013-01-01')->setRepeatTimes(2)->setFrequency(1);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-01-02', $this->dayRepeat->endDate());

        $this->repeatRule->setRepeatTimes(7)->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-01-13', $this->dayRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->repeatRule->reset();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->dayRepeat->repeatedDates('2013-01-01', '2013-02-01'));

        $this->repeatRule->reset()->setBaseDate('2013-01-01')->setRepeatByYear();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->dayRepeat->repeatedDates('2013-01-01', '2013-02-01'));

        $this->repeatRule->reset()->setBaseDate('2013-01-01')->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);
        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-01', '2013-02-01');
        $result = array(
            '2013-01-01',
            '2013-01-03',
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11',
            '2013-01-13',
            '2013-01-15',
            '2013-01-17',
            '2013-01-19',
            '2013-01-21',
            '2013-01-23',
            '2013-01-25',
            '2013-01-27',
            '2013-01-29',
            '2013-01-31'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-10', '2013-02-01');
        $result = array(
            '2013-01-11',
            '2013-01-13',
            '2013-01-15',
            '2013-01-17',
            '2013-01-19',
            '2013-01-21',
            '2013-01-23',
            '2013-01-25',
            '2013-01-27',
            '2013-01-29',
            '2013-01-31'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-10', '2013-01-10');
        $this->assertEquals(array(), $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-12');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-04', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2012-12-01', '2012-12-31');
        $result = array();
        $this->assertEquals($result, $repeatedDates);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-02-02', '2013-02-27');
        $result = array();
        $this->assertEquals($result, $repeatedDates);
    }
}
