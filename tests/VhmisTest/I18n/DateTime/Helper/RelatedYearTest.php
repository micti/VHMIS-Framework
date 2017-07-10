<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\DateTime\Helper;

use \Vhmis\I18n\DateTime\DateTime;
use \Vhmis\I18n\DateTime\Helper\RelatedYear;

class RelatedYearTest extends \PHPUnit\Framework\TestCase
{
    protected $relatedYear;

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
        $this->relatedYear = new RelatedYear;
    }

    public function testInvoke()
    {
        $a = $this->relatedYear;
        $this->assertEquals(null, $a('setDay', array(1)));
        $this->assertEquals(null, $a('setDay', 1));
    }

    public function testGet()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $date->setDate(31, 6, 30);
        $date->setField(0, 78); // 2014
        $this->relatedYear->setDateTimeObject($date);

        $this->assertEquals(2014, $this->relatedYear->get());
    }

    public function testSet()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $this->relatedYear->setDateTimeObject($date);
        $this->relatedYear->set(2014);

        $this->assertEquals(78, $date->getField(0));
        $this->assertEquals(31, $date->getField(1));
    }

    public function testModifyDate()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $this->relatedYear->setDateTimeObject($date);
        $this->relatedYear->modify('2014-06-05');

        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(6, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));
    }

    public function testModifyTime()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $this->relatedYear->setDateTimeObject($date);
        $this->relatedYear->modify('11:12:13');

        $this->assertEquals(11, $date->getField(11));
        $this->assertEquals(12, $date->getField(12));
        $this->assertEquals(13, $date->getField(13));
    }

    public function testModifyDateTime()
    {
        $date = new DateTime('GMT+07:00', 'chinese');
        $this->relatedYear->setDateTimeObject($date);
        $this->relatedYear->modify('2014-09-05 06:11:55');

        $this->assertEquals(31, $date->getField(1));
        $this->assertEquals(9, $date->getField(2));
        $this->assertEquals(5, $date->getField(5));
        $this->assertEquals(6, $date->getField(11));
        $this->assertEquals(11, $date->getField(12));
        $this->assertEquals(55, $date->getField(13));
    }
}
