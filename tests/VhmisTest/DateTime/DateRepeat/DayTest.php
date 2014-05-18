<?php

namespace VhmisTest\DateTime\DateRepeat;

use Vhmis\DateTime\DateRepeat\Day;

class DayTest extends \PHPUnit_Framework_TestCase
{

    public function testEndDate()
    {
        $dayRepeat = new Day('2014-01-01', '2014-02-02', 0, 1);

        $this->assertEquals('2014-02-02', $dayRepeat->endDate());

        $dayRepeat = new Day('2014-01-01', null, 1, 1);

        $this->assertEquals('2014-01-02', $dayRepeat->endDate());

        $dayRepeat = new Day('2014-01-01', null, 2, 1);

        $this->assertEquals('2014-01-03', $dayRepeat->endDate());
    }
}
