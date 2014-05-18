<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime\DateRepeat;

use \Vhmis\DateTime\DateTime;

/**
 * Caculation repeated dates by day
 */
class Day
{
    protected $startDate;
    protected $repeatedTimes;
    protected $freq;
    protected $endDate;

    /**
     * Construct
     *
     * @param string $startDate
     * @param array  $endDate
     * @param int    $times
     * @param int    $freq
     */
    public function __construct($startDate, $endDate, $times, $freq)
    {
        $this->startDate = $startDate;
        $this->freq = (int) $freq;
        $this->repeatedTimes = (int) $times;
        $this->endDate = $endDate;
    }

    /**
     * Caculate all repeated dates
     *
     * @param string|null $fromDate
     * @param string|null $toDate
     *
     * @return array
     */
    public function repeatedDates($fromDate = null, $toDate = null)
    {
        $repeatedDate = array();

        $base = new DateTime;
        $base->modify($this->startDate);

        $from = clone $base;
        if ($fromDate !== null) {
            $from->modify($fromDate);
        }

        $endDate = $this->endDate();
        $end = new DateTime;
        $end->modify($endDate);

        $to = clone $end;
        if ($toDate !== null) {
            $to->modify($toDate);
        }

        if ($end > $to) {
            $end = $to;
        }

        if ($base >= $from) {
            $repeatedDate[] = $base->formatISO(0);
        }

        while ($base < $end) {
            $base->addDay($this->freq);

            if ($base >= $from) {
                $repeatedDate[] = $base->formatISO(0);
            }
        }

        return $repeatedDate;
    }

    /**
     * Caculate end date of repeat
     *
     * @return string
     */
    public function endDate()
    {
        if ($this->endDate !== null) {
            return $this->endDate;
        }

        $date = new DateTime;
        $date->modify($this->startDate)->addDay($this->repeatedTimes * $this->freq);

        $this->endDate = $date->formatISO(0);

        return $this->endDate;
    }
}
