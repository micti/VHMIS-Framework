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
use \Vhmis\I18n\DateTime\Helper\Get;

class GetTest extends \PHPUnit\Framework\TestCase
{
    protected $get;
    protected $date;
    protected $icuVersion;

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
        
        $this->icuVersion = $this->getICUVersion();

        $this->get = new Get;
        $this->date = new DateTime;
        $this->get->setDateTimeObject($this->date);
    }

    public function testGetDate()
    {
        $this->date->setDate(2014, 12, 11);
        $this->assertEquals('2014-12-11', $this->get->getDate());
    }

    public function testGetDateWithExtendedYear()
    {
        $this->date->setDate(20141, 12, 11);
        $this->assertEquals('20141-12-11', $this->get->getDateWithExtendedYear());
    }

    public function testGetDateWithRelatedYear()
    {
        $this->checkICUVersion();
        $this->date->setDate(20141, 12, 11);
        $this->assertEquals('20141-12-11', $this->get->getDateWithRelatedYear());
    }

    public function testGetTime()
    {
        $this->date->setTime(11, 12, 11);
        $this->assertEquals('11:12:11', $this->get->getTime());
    }

    public function testGetDateTime()
    {
        $this->date->setDate(2014, 12, 11)->setTime(11, 12, 11);
        $this->assertEquals('2014-12-11 11:12:11', $this->get->getDateTime());
    }

    public function testGetDateTimeWithExtendedYear()
    {
        $this->date->setDate(2014, 12, 11)->setTime(11, 12, 11);
        $this->assertEquals('2014-12-11 11:12:11', $this->get->getDateTimeWithExtendedYear());
    }

    public function testGetDateTimeWithRelatedYear()
    {
        $this->checkICUVersion();
        $this->date->setDate(2014, 12, 11)->setTime(11, 12, 11);
        $this->assertEquals('2014-12-11 11:12:11', $this->get->getDateTimeWithRelatedYear());
    }

    public function testGetAll()
    {
        $this->date->setDate(2014, 12, 11)->setTime(11, 12, 11)->setField(14, 222);

        $this->assertEquals(1, $this->get->getEra());
        $this->assertEquals(2014, $this->get->getYear());
        $this->assertEquals(2014, $this->get->getExtendedYear());
        $this->assertEquals(12, $this->get->getMonth());
        $this->assertEquals(0, $this->get->getIsLeapMonth());
        $this->assertEquals(11, $this->get->getDay());
        $this->assertEquals(11, $this->get->getHour());
        $this->assertEquals(12, $this->get->getMinute());
        $this->assertEquals(11, $this->get->getSecond());
        $this->assertEquals(222, $this->get->getMillisecond());
    }
    
    protected function getICUVersion()
    {
        $reflector = new \ReflectionExtension('intl');
        ob_start();
        $reflector->info();
        $output = ob_get_clean();
        preg_match('/^ICU version => (.*)$/m', $output, $matches);
        $version = (float) $matches[1];
        
        return $version;
    }
    
    protected function checkICUVersion()
    {
        if ($this->icuVersion < 53.0) {
            $this->markTestSkipped(
                'Require ICU version >= 53 to get related year (using r symbol).'
            );
        }
    }
}
