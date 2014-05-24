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

class YearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Year Repeat object
     *
     * @var Vhmis\DateTime\DateRepeat\Year
     */
    protected $yearRepeat;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->yearRepeat = new Year('2014-05-12', null, 0, 1);
    }

    public function testEndDate()
    {
        $this->yearRepeat->setStartDate('2014-05-17')->setEndDate(null)->setRepeatTimes(0);
        $this->assertEquals('2100-31-21', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-17')->setEndDate('2014-07-09');
        $this->assertEquals('2014-07-09', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-17')->setEndDate(null)
            ->setType('day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatTimes(6)->setFreq(2);
        $this->assertEquals('2018-01-17', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-17')->setEndDate(null)
            ->setType('relative_day')->setRepeatedMonths(array(1, 5, 12))
            ->setRepeatedDay('saturday')
            ->setRepeatedDayPosition('third')
            ->setRepeatTimes(6)->setFreq(1);
        $this->assertEquals('2016-01-16', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-10')->setEndDate(null)
            ->setRepeatedDayPosition('second');
        $this->assertEquals('2016-01-09', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-03')->setEndDate(null)
            ->setRepeatedDayPosition('first');
        $this->assertEquals('2016-01-02', $this->yearRepeat->endDate());

        $this->yearRepeat->setStartDate('2014-05-31')->setEndDate(null)
            ->setRepeatedDayPosition('last');
        $this->assertEquals('2016-01-30', $this->yearRepeat->endDate());
    }

    public function testRepeatedDates()
    {
        $this->yearRepeat
            ->setStartDate('2014-05-12')
            ->setEndDate(null)
            ->setType('day')
            ->setRepeatedMonths(array(1, 12, 5))
            ->setRepeatTimes(6)
            ->setFreq(1);

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

        $this->yearRepeat
            ->setStartDate('2014-05-31')
            ->setEndDate(null)
            ->setType('day')
            ->setRepeatedMonths(array(2, 5))
            ->setRepeatTimes(3)
            ->setFreq(1);
        $result = array(
            '2014-05-31',
            '2015-03-03',
            '2015-05-31'
        );
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-04-20', '2015-06-12'));

        $this->yearRepeat
            ->setStartDate('2014-02-28')
            ->setEndDate(null)
            ->setType('relative_day')
            ->setRepeatedDayPosition(4)
            ->setRepeatedDay('day')
            ->setRepeatedMonths(array(2))
            ->setRepeatTimes(4)
            ->setFreq(1);
        $result = array(
            '2014-02-28',
            '2015-02-28',
            '2016-02-29',
            '2017-02-28'
        );
        $this->assertEquals($result, $this->yearRepeat->repeatedDates('2014-02-20', '2017-09-12'));
    }
}
