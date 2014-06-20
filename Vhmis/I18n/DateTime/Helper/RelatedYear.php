<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\Utils\Std\AbstractDateTimeHelper;
use \Vhmis\I18n\DateTime\DateTime;

class RelatedYear extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Adjust value
     *
     * @var array
     */
    protected $relatedYearAdjust = array(
        'gregorian'     => 622,
        'chinese'       => -2637,
        'coptic'        => 284,
        'dangi'         => -2333,
        'ethiopic'      => 8,
        'hebrew'        => -3760,
        'indian'        => 79,
        'islamic-civil' => 0,
        'islamic'       => 0,
        'japanese'      => 0,
        'persian'       => 0,
        'taiwan'        => 0,
        'buddhist'      => 0
    );

    /**
     * Not support __invoke
     *
     * @return null
     */
    public function __invoke()
    {
        return null;
    }

    /**
     * Get Gregorian related year
     *
     * @return int
     */
    public function get()
    {
        $year = $this->date->getField(19);

        return $this->exchange($year);
    }

    /**
     * Set Gregorian related year
     *
     * @param int $year
     *
     * @return RelatedYear
     */
    public function set($year)
    {
        $this->date->setField(19, $this->exchange($year, 'ralatedyear'));

        return $this;
    }

    /**
     * Convert related year and extended year
     *
     * @param int    $year
     * @param string $from
     *
     * @return int
     */
    protected function exchange($year, $from = 'extendyear')
    {
        $way = 1;
        $method = 'islamicYearToGregorianYear';

        if ($from !== 'extendyear') {
            $way = -1;
            $method = 'gregorianYearToIslamicYear';
        }

        $calendar = $this->date->getType();

        $year += $this->relatedYearAdjust[$calendar] * $way;
        if (strpos($calendar, 'islamic') !== false) {
            $year = $this->$method($year);
        }

        return $year;
    }

    /**
     * Get Gregorian related year from Islamic calendar year
     *
     * @param int $islamicYear
     *
     * @return int
     */
    protected function islamicYearToGregorianYear($islamicYear)
    {
        $cycle = ($islamicYear - 1396) / 67 - 1;
        $offset = -($islamicYear - 1396) % 67;
        $shift = 2 * $cycle + (($offset <= 33) ? 1 : 0);

        if ($islamicYear >= 1397) {
            $cycle = ($islamicYear - 1397) / 67;
            $offset = ($islamicYear - 1397) % 67;
            $shift = 2 * $cycle + (($offset >= 33) ? 1 : 0);
        }

        return $islamicYear + 579 - $shift;
    }

    /**
     * Get Islamic year from Gregorian related year
     *
     * @param int $gregorianYear
     *
     * @return int
     */
    protected function gregorianYearToIslamicYear($gregorianYear)
    {
        $cycle = ($gregorianYear - 1977) / 65;
        $offset = ($gregorianYear - 1977) % 65;
        $shift = 2 * $cycle + (($offset >= 32) ? 1 : 0);

        if ($gregorianYear < 1977) {
            $cycle = ($gregorianYear - 1976) / 65 - 1;
            $offset = -($gregorianYear - 1976) % 65;
            $shift = 2 * $cycle + (($offset <= 32) ? 1 : 0);
        }

        return $gregorianYear - 579 + $shift;
    }
}
