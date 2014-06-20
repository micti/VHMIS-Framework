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
use \Vhmis\I18n\DateTime\Helper\Convert;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    protected $convert;

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

        $this->convert = new Convert;
    }

    public function testConvert()
    {
        $date = new DateTime(null);
        $this->convert->setDate($date);
        $date->setDate(2014, 6, 3);

        $result = array(
            'origin' => '0031-05-06',
            //'extend' => '4651-05-06',
            //'relate' => '2014-05-06',
        );
        $this->assertEquals($result, $this->convert->to('chinese'));
        $this->assertEquals(array(), $this->convert->to('vietnamese'));
        $result = array(
            'origin' => '0026-06-03',
            //'extend' => '2014-06-03',
            //'relate' => '0026-06-03',
        );
        $this->assertEquals($result, $this->convert->to('japanese'));
        $result = array(
            'origin' => '0031-05-06',
            //'extend' => '4347-05-06',
            //'relate' => '2014-05-06',
        );
        $this->assertEquals($result, $this->convert->to('dangi'));
        $date->setDate(1964, 9, 6);
        $result = array(
            'origin' => '0041-08-01',
            //'extend' => '4297-08-01',
            //'relate' => '1964-08-01',
        );
        $this->assertEquals($result, $this->convert->to('dangi'));
    }
}
