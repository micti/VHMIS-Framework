<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Formatter;

use Vhmis\I18n\DateTime\DateTime;
use Vhmis\I18n\Formatter\DateTimeInterval;

class DateTimeIntervalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * DateTimeInterval object.
     *
     * @var DateTimeInterval
     */
    protected $dtInterval;

    public function setUp()
    {
        $this->markTestSkipped(
                'This test must be skipped. Only test with local dev Intl extension.'
        );

        $this->dtInterval = new DateTimeInterval;
    }

    public function testInterval()
    {
        $date1 = new DateTime();
        $date1->modify('2015-01-02');

        $date2 = new DateTime();
        $date2->modify('2016-01-02');

        $this->assertEquals('Tháng 1 năm 2015 - Tháng 1 năm 2016', $this->dtInterval->interval($date1, $date2, 'yMMM', 'vi_VN'));
        $date2->modify('2015-04-23');
        $this->assertEquals('Tháng 1 - Tháng 4 năm 2015', $this->dtInterval->interval($date2, $date1, 'yMMM', 'vi_VN'));
    }

    public function testIntervalFallbackType()
    {
        $date1 = new DateTime();
        $date1->modify('2015-01-02 12:12:34');

        $date2 = new DateTime();
        $date2->modify('2016-01-02 12:13:56');

        $this->assertEquals('12:12 02/01/2015 - 12:13 02/01/2016', $this->dtInterval->interval($date1, $date2, 'intervalFormatFallback', 'vi_VN'));
    }

    public function testIntervalNotValidGreatestField()
    {
        $date1 = new DateTime();
        $date1->modify('2015-01-02 12:12:34');

        $date2 = new DateTime();
        $date2->modify('2015-01-02 11:13:56');

        $this->assertEquals('11:13 02/01/2015 - 12:12 02/01/2015', $this->dtInterval->interval($date1, $date2, 'intervalFormatFallback', 'vi_VN'));
    }

    public function testIntervalSameDate()
    {
        $date1 = new DateTime();
        $date1->modify('2015-01-02 12:12:34');

        $date2 = new DateTime();
        $date2->modify('2015-01-02 12:12:32');

        $this->assertEquals('12:12 02/01/2015', $this->dtInterval->interval($date1, $date2, 'intervalFormatFallback', 'vi_VN'));
    }
}
