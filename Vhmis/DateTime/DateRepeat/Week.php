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
 * Caculation repeated dates by week
 */
class Week extends AbstractRepeat
{
    /**
     * Days in week for repeat
     *
     * @var int[]
     */
    protected $repeatedWeekdays;

    /**
     * Set repeated weekdays
     * Use 0-6 for sunday to staturday
     * Accept int array or int string spec by ','
     *
     * @param int[]|string $weekdays
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     *
     * @throws \InvalidArgumentException
     */
    public function setRepeatWeekdays($weekdays)
    {
        $weekdays = is_string($weekdays) ? explode(',', $weekdays) : $weekdays;

        // Check
        foreach ($weekdays as &$weekday) {
            $weekday = (int) $weekday;
            if ($weekday < 0 || $weekday > 6) {
                throw new \InvalidArgumentException('Only int array or int string spec by `,`. From 0 - 6');
            }
        }

        $this->sortRepeatedWeekday($weekdays);

        return $this;
    }

    /**
     * Caculate all repeated dates
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

        // Skip some weeks
        if ($this->begin < $this->from) {
            $run->addWeek(ceil($run->diffWeek($this->from) / $this->freq) * $this->freq);
        }

        $run->modifyThisWeek($this->weekday[$this->repeatedWeekdays[0]]);

        $total = count($this->repeatedWeekdays);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDate[] = $run->formatISO(0);
            }

            $day = array_search($run->format('w'), $this->repeatedWeekdays);

            if ($day === $total - 1) {
                $run->addWeek($this->freq);
                $run->modifyThisWeek($this->weekday[$this->repeatedWeekdays[0]]);
            } else {
                $run->modifyThisWeek($this->weekday[$this->repeatedWeekdays[($day + 1)]]);
            }
        }

        return $repeatedDate;
    }

    /**
     * Caculate end date of repeat in range
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
        $date->modify($this->startDate);

        $position = array_search($this->startWeekday, $this->repeatedWeekdays);

        if ($position !== 0) {
            $date->modify('previous ' . $this->weekday[$this->repeatedWeekdays[0]]);
        }

        $repeatedWeek = ceil(($this->repeatedTimes + $position) / count($this->repeatedWeekdays)) - 1;
        $mod = ($this->repeatedTimes + $position) % count($this->repeatedWeekdays);
        $endDayPosition = $mod === 0 ? count($this->repeatedWeekdays) - 1 : $mod - 1;
        $date->addWeek($repeatedWeek * $this->freq);

        if ($endDayPosition === 0) {
            $this->endDate = $date->formatISO(0);

            return $this->endDate;
        }

        $date->modify('next ' . $this->weekday[$this->repeatedWeekdays[$endDayPosition]]);
        $this->endDate = $date->formatISO(0);

        return $this->endDate;
    }

    /**
     * Range repeated days based on start day of week
     *
     * @param array $weekdays
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     */
    protected function sortRepeatedWeekday($weekdays)
    {
        $weekdays = array_unique($weekdays);
        sort($weekdays);

        $position = array_search($this->startDayOfWeek, $this->weekday);

        $before = array();
        $after = array();

        for ($i = 0; $i < count($weekdays); $i++) {
            if ($position <= $weekdays[$i]) {
                $before[] = $weekdays[$i];
            } else {
                $after[] = $weekdays[$i];
            }
        }

        $this->repeatedWeekdays = array_unique(array_merge($before, $after));

        return $this;
    }
}
