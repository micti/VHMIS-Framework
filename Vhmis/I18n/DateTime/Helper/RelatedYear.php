<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\Utils\DateTime as DateTimeUtils;

class RelatedYear extends AbstractHelper
{
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
     * @param string $name
     * @param array  $arguments
     *
     * @return null
     */
    public function __invoke($name, $arguments)
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
     * Set date or/and time by ISO style datetime
     * Year is related year
     *
     * @param string $string
     *
     * @return RelatedYear
     */
    public function modify($string)
    {
        $result = DateTimeUtils::praseFormat($string);

        if (isset($result['date'])) {
            $year = $this->exchange((int) $result['date']['year'], 'ralatedyear');
            $this->date->setDateWithExtenedYear($year, (int) $result['date']['month'], (int) $result['date']['day']);
        }

        if (isset($result['time'])) {
            $this->date->setTime(
                (int) $result['time']['hour'], (int) $result['time']['minute'], (int) $result['time']['second']
            );
        }

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

        if ($from !== 'extendyear') {
            $way = -1;
        }

        $calendar = $this->date->getType();

        $year += $this->relatedYearAdjust[$calendar] * $way;
        if (strpos($calendar, 'islamic') !== false) {
            $year = $this->exchangeIslamicYear($year, $from);
        }

        return $year;
    }

    /**
     * Convert related year and extended year for Islamic calendar
     *
     * @param int    $year
     * @param string $from
     *
     * @return int
     */
    protected function exchangeIslamicYear($year, $from = 'extendyear')
    {
        $data = array(1976, 65, 32);

        if ($from !== 'extendyear') {
            $data = array(1936, 67, 33);
        }

        $cycle = ($year - $data[0]) / $data[1] - 1;
        $offset = -($year - $data[0]) % $data[1];

        if ($year >= $data[0] + 1) {
            $cycle = ($year - $data[0] - 1) / $data[1];
            $offset = ($year - $data[0] - 1) % $data[1];
        }

        $shift = 2 * $cycle + (($offset >= $data[2]) ? 1 : 0);

        return $year + 579 - $shift;
    }
}
