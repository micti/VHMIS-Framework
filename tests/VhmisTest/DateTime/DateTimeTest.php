<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\DateTime;

use Vhmis\DateTime\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * DateTime object
     *
     * @var Vhmis\DateTime\DateTime
     */
    protected $date;

    public function setUp()
    {
        $this->date = new DateTime;
    }

    /**
     * Test modifyThisWeek method
     */
    public function testModifyThisWeek()
    {
        $this->date->setStartDayOfWeek('monday');

        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('first day')->formatISO(0));
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-20', $this->date->modifyThisWeek('tuesday')->formatISO(0));
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-21', $this->date->modifyThisWeek('wednesday')->formatISO(0));
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-25', $this->date->modifyThisWeek('last day')->formatISO(0));

        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('first day')->formatISO(0));
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('monday')->formatISO(0));
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-22', $this->date->modifyThisWeek('thursday')->formatISO(0));
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-25', $this->date->modifyThisWeek('last day')->formatISO(0));

        $this->date->setStartDayOfWeek('sunday');

        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-18', $this->date->modifyThisWeek('first day')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('monday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-20', $this->date->modifyThisWeek('tuesday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-21', $this->date->modifyThisWeek('wednesday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-22', $this->date->modifyThisWeek('thursday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-23', $this->date->modifyThisWeek('friday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-24', $this->date->modifyThisWeek('saturday')->formatISO(0));
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-18', $this->date->modifyThisWeek('sunday')->formatISO(0));
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-24', $this->date->modifyThisWeek('last day')->formatISO(0));

        $this->date->setStartDayOfWeek('saturday');

        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-26', $this->date->modifyThisWeek('monday')->formatISO(0));
        $this->date->modify('2014-05-30');
        $this->assertEquals('2014-05-26', $this->date->modifyThisWeek('monday')->formatISO(0));
    }
}
