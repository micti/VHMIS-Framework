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
use \Vhmis\I18n\DateTime\Helper\Format;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    protected $format;
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

        $reflector = new \ReflectionExtension('intl');
        ob_start();
        $reflector->info();
        $output = ob_get_clean();
        preg_match('/^ICU version => (.*)$/m', $output, $matches);
        if ($matches[1] < '5') {
            $this->markTestSkipped(
                'ICU version > 5 is not available.'
            );
        }

        $this->format = new Format;
        $this->date = new DateTime('Asia/Ho_Chi_Minh', '', 'en_US');
        $this->format->setDate($this->date);
    }

    public function testFormatFull()
    {
        $this->date->setDate(2014, 6, 3)->setTime(14, 8, 43);
        $this->assertEquals('Tuesday, June 3, 2014 at 2:08:43 PM Indochina Time', $this->format->formatFull());
    }

    public function testFormatLong()
    {
        $this->date->setDate(2014, 6, 3)->setTime(14, 8, 43);
        $this->assertEquals('June 3, 2014 at 2:08:43 PM GMT+7', $this->format->formatLong());
    }

    public function testFormatMedium()
    {
        $this->date->setDate(2014, 6, 3)->setTime(14, 8, 43);
        $this->assertEquals('Jun 3, 2014, 2:08:43 PM', $this->format->formatMedium());
    }

    public function testFormatShort()
    {
        $this->date->setDate(2014, 6, 3)->setTime(14, 8, 43);
        $this->assertEquals('6/3/14, 2:08 PM', $this->format->formatShort());
    }
}
