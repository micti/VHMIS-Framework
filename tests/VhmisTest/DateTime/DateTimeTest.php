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
     * Test addMonth method
     */
    public function testAddMonth()
    {
        $this->date->modify('2014-05-20');
        $this->assertEquals('2014-06-20', $this->date->addMonth(1)->formatISO(0));
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-06-30', $this->date->addMonth(1)->formatISO(0));
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-02-28', $this->date->addMonth(-3)->formatISO(0));
        $this->date->modify('2014-05-31');
        $this->assertEquals('2015-05-31', $this->date->addMonth(12)->formatISO(0));
        $this->date->modify('2014-05-31');
        $this->assertEquals('2012-12-31', $this->date->addMonth(-17)->formatISO(0));
    }

    /**
     * Test diffDate method
     */
    public function testDiffDay()
    {
        $this->date->modify('2014-05-20 23:11:34');

        $date = new DateTime('2014-06-20 00:00:00');
        $this->assertEquals(31, $this->date->diffDay($date));

        $date->modify('2014-05-20');
        $this->assertEquals(0, $this->date->diffDay($date));

        $date->modify('2014-01-01');
        $this->assertEquals(0 - $this->date->format('z'), $this->date->diffDay($date));
    }

    /**
     * Test diffWeek method
     */
    public function testDiffWeek()
    {
        $this->date->setStartDayOfWeek('sunday')->modify('2014-05-19 00:52:34');
        $date = new DateTime('2014-05-18 23:11:34');

        $this->assertEquals(0, $this->date->diffWeek($date));

        $this->date->setStartDayOfWeek('monday');
        $this->assertEquals(-1, $this->date->diffWeek($date));

        $date->modify('2014-06-29');
        $this->assertEquals(5, $this->date->diffWeek($date));
    }

    /**
     * Test diffMonth method
     */
    public function testDiffMonth()
    {
        $this->date->modify('2014-05-19 00:52:34');
        $date = new DateTime('2014-05-18 23:11:34');

        $this->assertEquals(0, $this->date->diffMonth($date));

        $date->modify('2015-12-29');
        $this->assertEquals(19, $this->date->diffMonth($date));

        $date->modify('2014-02-28');
        $this->assertEquals(-3, $this->date->diffMonth($date));
    }

    /**
     * Test diffYear method
     */
    public function testDiffYear()
    {
        $this->date->modify('0000-01-12 00:52:34');

        $date = new DateTime('2014-05-18 23:11:34');
        $this->assertEquals(2014, $this->date->diffYear($date));

        $this->date->modify('2010-12-29');
        $this->assertEquals(4, $this->date->diffYear($date));

        $date->modify('2010-02-28');
        $this->assertEquals(0, $this->date->diffYear($date));
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

    public function testSetDay()
    {
        $this->date->modify('2014-05-19');
        $this->date->setDay(1);
        $this->assertEquals('2014-05-01', $this->date->formatISO(0));
        $this->date->setDay(0);
        $this->assertEquals('2014-05-01', $this->date->formatISO(0));
        $this->date->setDay(32);
        $this->assertEquals('2014-05-31', $this->date->formatISO(0));
    }

    public function testSetMonth()
    {
        $this->date->modify('2014-05-19');
        $this->date->setMonth(4);
        $this->assertEquals('2014-04-19', $this->date->formatISO(0));

        $this->date->modify('2014-03-31');
        $this->date->setMonth(2);
        $this->assertEquals('2014-02-28', $this->date->formatISO(0));
    }

    public function testSetYear()
    {
        $this->date->modify('2014-05-19');
        $this->date->setYear(2078);
        $this->assertEquals('2078-05-19', $this->date->formatISO(0));

        $this->date->modify('2016-02-29');
        $this->date->setYear(2015);
        $this->assertEquals('2015-02-28', $this->date->formatISO(0));
    }

    public function testMagicMethod()
    {
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-18', $this->date->setYesterday()->formatISO(0));
        $this->assertEquals('2014-05-19', $this->date->setTomorrow()->formatISO(0));
        $this->assertEquals('2014-05-01', $this->date->setFirstDayOfMonth()->formatISO(0));
        $this->assertEquals('2014-05-31', $this->date->setLastDayOfMonth()->formatISO(0));
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-05-26', $this->date->setFirstDayOfWeek()->formatISO(0));
        $this->assertEquals('2014-06-01', $this->date->setLastDayOfWeek()->formatISO(0));

        $this->date->modify('2014-05-19');
        $date1 = $this->date->getYesterday();
        $this->assertNotSame($date1, $this->date);
        $this->assertEquals('2014-05-18', $date1->formatISO(0));
        $date2 = $this->date->getTomorrow();
        $this->assertNotSame($date2, $this->date);
        $this->assertEquals('2014-05-20', $date2->formatISO(0));
        $date3 = $this->date->getFirstDayOfMonth();
        $this->assertNotSame($date3, $this->date);
        $this->assertEquals('2014-05-01', $date3->formatISO(0));
        $date4 = $this->date->getLastDayOfMonth();
        $this->assertNotSame($date4, $this->date);
        $this->assertEquals('2014-05-31', $date4->formatISO(0));

        $date5 = $this->date->wrongMethod();
        $this->assertSame($date5, $this->date);
    }
}
