<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Utils;

use \Vhmis\Utils\DateTime as DateTimeUtils;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testSortWeekday()
    {
        $this->assertEquals(array(3,4,5,6,7,1,2), DateTimeUtils::sortWeekday(3));
    }

    public function testSortWeekday1()
    {
        $this->assertEquals(array(1,2,3,4,5,6,7), DateTimeUtils::sortWeekday(1));
    }

    public function testSortWeekday2()
    {
        $this->assertEquals(array(7,1,2,3,4,5,6), DateTimeUtils::sortWeekday(7));
    }

    public function testSortWeekdayOutParam()
    {
        $this->assertEquals(array(1,2,3,4,5,6,7), DateTimeUtils::sortWeekday(0));
    }

    public function testSortWeekdayOutParam2()
    {
        $this->assertEquals(array(1,2,3,4,5,6,7), DateTimeUtils::sortWeekday(8));
    }

    public function testGetPositionOfWeekdayFromSortedWeekdayList()
    {
        $list = array(2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4);
        $days = array(6);

        $this->assertEquals(array(4,11,18,25), DateTimeUtils::getPositionOfWeekdayFromSortedWeekdayList($days, $list));
    }

    public function testGetPositionOfWeekdayFromSortedWeekdayList1()
    {
        $list = array(2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4);
        $days = array(7,1);

        $this->assertEquals(array(5,6,12,13,19,20,26,27), DateTimeUtils::getPositionOfWeekdayFromSortedWeekdayList($days, $list));
    }

    public function testGetPositionOfWeekdayFromSortedWeekdayList2()
    {
        $list = array(2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4);
        $days = array(0);

        $this->assertEquals(array(), DateTimeUtils::getPositionOfWeekdayFromSortedWeekdayList($days, $list));
    }

    public function testGetPositionOfWeekdayFromSortedWeekdayList3()
    {
        $list = array(2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4);
        $days = array(1,2,3,4,5,6,7);

        $this->assertEquals(
            array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30),
            DateTimeUtils::getPositionOfWeekdayFromSortedWeekdayList($days, $list)
        );
    }
}
