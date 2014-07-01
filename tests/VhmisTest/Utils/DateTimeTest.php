<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n;

use \Vhmis\Utils\DateTime as DateTimeUtils;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testSortWeekday()
    {
        $this->assertEquals(array(3,4,5,6,7,1,2), DateTimeUtils::sortWeekday(3));
        $this->assertEquals(array(1,2,3,4,5,6,7), DateTimeUtils::sortWeekday(1));
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
}
