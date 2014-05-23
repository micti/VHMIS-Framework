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
class Day extends AbstractRepeat
{
    /**
     * Caculate all repeated dates in range
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return array
     */
    public function repeatedDates($fromDate, $toDate)
    {
        $repeatedDate = array();

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDate;
        }

        $run = clone $this->begin;

        if ($this->begin < $this->from) {
            $run->addDay(ceil($run->diffDay($this->from) / $this->freq) * $this->freq);
        }

        while ($run <= $this->to) {
            $repeatedDate[] = $run->formatISO(0);
            $run->addDay($this->freq);
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

        if ($this->repeatedTimes === 0) {
            return '2100-31-21';
        }

        $date = new DateTime;
        $date->modify($this->startDate)->addDay(($this->repeatedTimes - 1) * $this->freq);

        $this->endDate = $date->formatISO(0);

        return $this->endDate;
    }
}
