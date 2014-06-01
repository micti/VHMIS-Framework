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

    public function testCreateFromFormat()
    {
        $date = DateTime::createFromFormat('Y-m-d', '2005-12-12');
        $this->assertInstanceOf('DateTime', $date);

        $false = DateTime::createFromFormat('Y-m-d', '2005-a-12');
        $this->assertEquals(false, $false);
    }

    public function testStartOfWeek()
    {
        $this->date->setStartOfWeek('thursday');
        $this->assertEquals('thursday', $this->date->getStartOfWeek());

        $this->date->setStartOfWeek(0);
        $this->assertEquals('sunday', $this->date->getStartOfWeek());

        $this->date->setStartOfWeek(9);
        $this->assertEquals('sunday', $this->date->getStartOfWeek());
    }

    public function testFormat()
    {
        $this->date->modify('2014-05-02 12:12:14');

        $this->assertEquals('2014-05-02 12:12:14', $this->date->formatISODateTime());
        $this->assertEquals('2014-05-02', $this->date->formatISODate());
        $this->assertEquals('2014-05', $this->date->formatISOYearMonth());

        $this->assertEquals('2014-05-02 12:12:14', $this->date->formatSQLDateTime());
        $this->assertEquals('2014-05-02', $this->date->formatSQLDate());
    }

    /**
     * Test addMonth method
     */
    public function testAddMonth()
    {
        $this->date->modify('2014-05-20');
        $this->assertEquals('2014-06-20', $this->date->addMonth(1)->formatISODate());
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-06-30', $this->date->addMonth(1)->formatISODate());
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-02-28', $this->date->addMonth(-3)->formatISODate());
        $this->date->modify('2014-05-31');
        $this->assertEquals('2015-05-31', $this->date->addMonth(12)->formatISODate());
        $this->date->modify('2014-05-31');
        $this->assertEquals('2012-12-31', $this->date->addMonth(-17)->formatISODate());
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
        $this->date->setStartOfWeek('sunday')->modify('2014-05-19 00:52:34');
        $date = new DateTime('2014-05-18 23:11:34');

        $this->assertEquals(0, $this->date->diffWeek($date));

        $this->date->setStartOfWeek('monday');
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
        $this->date->setStartOfWeek('monday');

        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('first day')->formatISODate());
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-20', $this->date->modifyThisWeek('tuesday')->formatISODate());
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-21', $this->date->modifyThisWeek('wednesday')->formatISODate());
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-25', $this->date->modifyThisWeek('last day')->formatISODate());

        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('first day')->formatISODate());
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('monday')->formatISODate());
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-22', $this->date->modifyThisWeek('thursday')->formatISODate());
        $this->date->modify('2014-05-25');
        $this->assertEquals('2014-05-25', $this->date->modifyThisWeek('last day')->formatISODate());

        $this->date->setStartOfWeek('sunday');

        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-18', $this->date->modifyThisWeek('first day')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-19', $this->date->modifyThisWeek('monday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-20', $this->date->modifyThisWeek('tuesday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-21', $this->date->modifyThisWeek('wednesday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-22', $this->date->modifyThisWeek('thursday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-23', $this->date->modifyThisWeek('friday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-24', $this->date->modifyThisWeek('saturday')->formatISODate());
        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-18', $this->date->modifyThisWeek('sunday')->formatISODate());
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-24', $this->date->modifyThisWeek('last day')->formatISODate());

        $this->date->setStartOfWeek('saturday');

        $this->date->modify('2014-05-24');
        $this->assertEquals('2014-05-26', $this->date->modifyThisWeek('monday')->formatISODate());
        $this->date->modify('2014-05-30');
        $this->assertEquals('2014-05-26', $this->date->modifyThisWeek('monday')->formatISODate());
    }

    public function testFindRelative()
    {
        $date = new DateTime;
        $this->date->setStartOfWeek(1)->modify('2015-05-06');
        $date->setStartOfWeek(1)->modify('2017-05-06');
        $relative = $this->date->findRelative($date);
        $this->assertEquals(array(), $relative);

        $this->date->modify('2015-05-06');
        $date->modify('2016-05-06');
        $relative = $this->date->findRelative($date);
        $this->assertEquals(array('y' => -1), $relative);

        $this->date->modify('2014-06-01');
        $date->modify('2014-05-31');
        $relative = $this->date->findRelative($date);
        $this->assertEquals(array('d' => 1, 'w' => 0, 'm' => 1, 'y' => 0), $relative);

        $this->date->modify('2014-06-05');
        $date->modify('2014-06-13');
        $relative = $this->date->findRelative($date);
        $this->assertEquals(array('w' => -1, 'm' => 0, 'y' => 0), $relative);

        $this->date->modify('2014-06-05');
        $date->modify('2014-05-13');
        $relative = $this->date->findRelative($date);
        $this->assertEquals(array('m' => 1, 'y' => 0), $relative);
    }

    public function testSetDay()
    {
        $this->date->modify('2014-05-19');
        $this->date->setDay(1);
        $this->assertEquals('2014-05-01', $this->date->formatISODate());
        $this->date->setDay(0);
        $this->assertEquals('2014-04-30', $this->date->formatISODate());
        $this->date->setDay(32);
        $this->assertEquals('2014-05-02', $this->date->formatISODate());
    }

    public function testSetMonth()
    {
        $this->date->modify('2014-05-19');
        $this->date->setMonth(4);
        $this->assertEquals('2014-04-19', $this->date->formatISODate());

        $this->date->modify('2014-03-31');
        $this->date->setMonth(2);
        $this->assertEquals('2014-02-28', $this->date->formatISODate());
    }

    public function testSetYear()
    {
        $this->date->modify('2014-05-19');
        $this->date->setYear(2078);
        $this->assertEquals('2078-05-19', $this->date->formatISODate());

        $this->date->modify('2016-02-29');
        $this->date->setYear(2015);
        $this->assertEquals('2015-02-28', $this->date->formatISODate());
    }

    public function testMagicMethod()
    {
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014-05-18', $this->date->setYesterday()->formatISODate());
        $this->assertEquals('2014-05-19', $this->date->setTomorrow()->formatISODate());
        $this->assertEquals('2014-05-01', $this->date->setFirstDayOfMonth()->formatISODate());
        $this->assertEquals('2014-05-31', $this->date->setLastDayOfMonth()->formatISODate());
        $this->date->modify('2014-05-31');
        $this->assertEquals('2014-05-26', $this->date->setFirstDayOfWeek()->formatISODate());
        $this->assertEquals('2014-06-01', $this->date->setLastDayOfWeek()->formatISODate());

        $this->date->modify('2014-05-19');
        $date1 = $this->date->getYesterday();
        $this->assertNotSame($date1, $this->date);
        $this->assertEquals('2014-05-18', $date1->formatISODate());
        $date2 = $this->date->getTomorrow();
        $this->assertNotSame($date2, $this->date);
        $this->assertEquals('2014-05-20', $date2->formatISODate());
        $date3 = $this->date->getFirstDayOfMonth();
        $this->assertNotSame($date3, $this->date);
        $this->assertEquals('2014-05-01', $date3->formatISODate());
        $date4 = $this->date->getLastDayOfMonth();
        $this->assertNotSame($date4, $this->date);
        $this->assertEquals('2014-05-31', $date4->formatISODate());

        $date5 = $this->date->wrongMethod();
        $this->assertSame($date5, $this->date);
    }

    public function testGetMonth()
    {
        $this->date->modify('2014-05-19');
        $this->assertEquals('05', $this->date->getMonth());
    }

    public function testGetYear()
    {
        $this->date->modify('2014-05-19');
        $this->assertEquals('2014', $this->date->getYear());
    }

    public function testGetDay()
    {
        $this->date->modify('2014-05-02');
        $this->assertEquals('02', $this->date->getDay());
    }

    public function testGetWeekday()
    {
        $this->date->modify('2014-05-26');
        $this->assertEquals('1', $this->date->getWeekday());

        $this->date->modify('2014-06-01');
        $this->assertEquals('7', $this->date->getWeekday());
    }

    public function testGetFirstDayOfWeek()
    {
        $this->date->modify('2014-05-31');
        $date = $this->date->getFirstDayOfWeek();
        $this->assertNotSame($date, $this->date);
        $this->assertEquals('2014-05-26', $date->formatISODate());
    }

    public function testGetLastDayOfWeek()
    {
        $this->date->modify('2014-05-31');
        $date = $this->date->getLastDayOfWeek();
        $this->assertNotSame($date, $this->date);
        $this->assertEquals('2014-06-01', $date->formatISODate());
    }
}
