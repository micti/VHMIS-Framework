<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\DateRepeat;

use Vhmis\I18n\DateTime\DateRepeat\Day;
use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\DateTime\DateRepeat\Rule;

class DayTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Day Repeat object
     *
     * @var Day
     */
    protected $dayRepeat;

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
        
        $this->dayRepeat = new Day();
        $this->repeatRule = new Rule();
        $this->date = new DateTime('Asia/Ho_Chi_Minh');
        $this->date->modify('2013-01-01');
    }

    public function testEndDate1()
    {
        $this->repeatRule->reset();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());
    }

    public function testEndDate2()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(5);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());
    }

    public function testEndDate3()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2100-12-31', $this->dayRepeat->endDate());
    }

    public function testEndDate4()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-02');
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-02-02', $this->dayRepeat->endDate());
    }

    public function testEndDate5()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setRepeatTimes(2)->setFrequency(1);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-01-02', $this->dayRepeat->endDate());
    }

    public function testEndDate6()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setRepeatTimes(7)->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals('2013-01-13', $this->dayRepeat->endDate());
    }

    public function testRepeatedDates1()
    {
        $this->repeatRule->reset();
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->dayRepeat->repeatedDates('2013-01-01', '2013-02-01'));
    }

    public function testRepeatedDates2()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(5);
        $this->dayRepeat->setRule($this->repeatRule);
        $this->assertEquals(array(), $this->dayRepeat->repeatedDates('2013-01-01', '2013-02-01'));
    }

    public function testRepeatedDates3()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
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
    }

    public function testRepeatedDates4()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);
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
    }

    public function testRepeatedDates5()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-10', '2013-01-10');
        $this->assertEquals(array(), $repeatedDates);
    }

    public function testRepeatedDates6()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-12');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);
    }

    public function testRepeatedDates7()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-04', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);
    }

    public function testRepeatedDates8()
    {
        $this->repeatRule->reset()->setBaseDate($this->date)->setRepeatBy(4);
        $this->repeatRule->setEndDate('2013-02-01')->setFrequency(2);
        $this->dayRepeat->setRule($this->repeatRule);

        $repeatedDates = $this->dayRepeat->repeatedDates('2013-01-05', '2013-01-11');
        $result = array(
            '2013-01-05',
            '2013-01-07',
            '2013-01-09',
            '2013-01-11'
        );
        $this->assertEquals($result, $repeatedDates);
    }
}
