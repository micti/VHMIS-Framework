<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Validator;

use Vhmis\Validator\DateTime;

class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Validator object
     *
     * @var DateTime
     */
    protected $dateTimeValidator;

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

        locale_set_default('en_US');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $this->dateTimeValidator = new DateTime();
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\MissingOptionException
     */
    public function testMissingOption()
    {
        $this->dateTimeValidator->reset();
        $this->dateTimeValidator->isValid('8/8/2014');
    }

    public function testIsValid()
    {
        // us - M/d/yy
        $this->dateTimeValidator->setOptions(['pattern' => 'M/d/yy']);
        $this->assertTrue($this->dateTimeValidator->isValid('8/8/2014'));
        $this->assertTrue($this->dateTimeValidator->isValid('8/8/14'));
        $this->assertFalse($this->dateTimeValidator->isValid('13/8/14'));
        $this->assertFalse($this->dateTimeValidator->isValid('34/8/14'));
        $this->assertFalse($this->dateTimeValidator->isValid('a'));
        $this->assertFalse($this->dateTimeValidator->isValid(new \stdClass));
        
        $this->dateTimeValidator->setOptions(['pattern' => 'dd/MM/y']);
        $this->assertTrue($this->dateTimeValidator->isValid('12/02/2015'));
        $this->assertTrue($this->dateTimeValidator->isValid('12/02/15'));
        $this->assertTrue($this->dateTimeValidator->isValid('12/2/15'));
        $this->assertFalse($this->dateTimeValidator->isValid('29/2/15'));
    }
}
