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
 * Caculation repeated dates by month
 */
class Year extends AbstractRepeat
{
    /**
     * Repeat type
     *
     * @var string
     */
    protected $type = 'day';

    /**
     * Position of day in month
     *
     * @var array
     */
    protected $dayPositions = array(
        'first',
        'second',
        'third',
        'fourth',
        'last'
    );

    /**
     * Days
     *
     * @var array
     */
    protected $days = array(
        '0' => 'sunday',
        '1' => 'monday',
        '2' => 'tuesday',
        '3' => 'wednesday',
        '4' => 'thursday',
        '5' => 'friday',
        '6' => 'saturday',
        '7' => 'day'
    );

    /**
     * Position of day of repeated date
     *
     * @var int
     */
    protected $repeatedDayPosition;

    /**
     * Day of repeated day
     *
     * @var int
     */
    protected $repeatedDay;

    /**
     *
     * @var int[]
     */
    protected $repeatedMonths;

    /**
     * Set type of repeat
     *
     * - Repeat by day in month: 24th August ...
     * - Repeat by relative day in month: first Monday of September
     */
    public function setType($type)
    {
        $this->type = 'day';

        if ($type === 'relative_day') {
            $this->type = 'relative_day';
        }

        return $this;
    }

    /**
     * Set day of repeated day in month
     * 0 - 6 for sunday to saturday, 7 for a common day in month
     *
     * @param int position
     */
    public function setRepeatedDay($day)
    {
        if (is_numeric($day) && $day <= 7 && $day >= 0) {
            $this->repeatedDay = $day;

            return $this;
        }

        $day = array_search($day, $this->days);
        if ($day !== false) {
            $this->repeatedDay = $day;

            return $this;
        }

        return $this;
    }

    /**
     * Set position of day of repeated day in month
     * (for type = relative_day)
     *
     * @param string|int position
     */
    public function setRepeatedDayPosition($position)
    {
        if (is_numeric($position) && $position <= 4 && $position >= 0) {
            $this->repeatedDayPosition = $position;

            return $this;
        }

        $position = array_search($position, $this->dayPositions);
        if ($position !== false) {
            $this->repeatedDayPosition = $position;

            return $this;
        }

        return $this;
    }

    /**
     * Set repeated months, use 1-12, accept int array or int string spec by ','
     *
     * @param int[]|string $months
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     *
     * @throws \InvalidArgumentException
     */
    public function setRepeatedMonths($months)
    {
        $months = is_string($months) ? explode(',', $months) : $months;

        // Check
        foreach ($months as &$month) {
            $month = (int) $month;
            if ($month < 0 || $month > 12) {
                throw new \InvalidArgumentException('Only int array or int string spec by `,`. From 1 - 12');
            }
        }

        $months = array_unique($months);
        sort($months);
        $this->repeatedMonths = $months;

        return $this;
    }

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
        $run->setDay(1);

        // Skip some years
        if ($this->begin < $this->from) {
            $run->addYear(ceil($run->diffYear($this->from) / $this->freq) * $this->freq);
        }

        $month = (int) $run->getMonth();
        $this->setDayForMonth($run);
        $total = count($this->repeatedMonths);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISO(0);
            }

            // Prevent run date goes to next month
            $monthPosition = array_search($month, $this->repeatedMonths);
            $run->setDay(1);

            if ($monthPosition === $total - 1) {
                $run->addYear($this->freq);
                $month = $this->repeatedMonths[0];
            } else {
                $month = $this->repeatedMonths[($monthPosition + 1)];
            }
            $run->setMonth($month);
            $this->setDayForMonth($run);
        }

        return $repeatedDates;
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
        $date->modify($this->startDate)->setDay(1);

        $position = array_search($this->startMonth, $this->repeatedMonths);

        if ($position !== 0) {
            $date->setMonth($this->repeatedMonths[0]);
        }

        $repeatedYear = ceil(($this->repeatedTimes + $position) / count($this->repeatedMonths)) - 1;
        $mod = ($this->repeatedTimes + $position) % count($this->repeatedMonths);
        $endMonthPosition = $mod === 0 ? count($this->repeatedMonths) - 1 : $mod - 1;
        $date->addYear($repeatedYear * $this->freq);

        if ($endMonthPosition === 0) {
            $this->setDayForMonth($date);
            $this->endDate = $date->formatISO(0);

            return $this->endDate;
        }

        $date->setMonth($this->repeatedMonths[$endMonthPosition]);
        $this->setDayForMonth($date);
        $this->endDate = $date->formatISO(0);

        return $this->endDate;
    }

    /**
     * Set date for month based on type
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return \Vhmis\DateTime\DateRepeat\Year
     */
    protected function setDayForMonth($date)
    {
        if ($this->type === 'day') {
            $date->setDay($this->startDay);

            return $this;
        }

        $date->modify(
            $this->dayPositions[$this->repeatedDayPosition]
            . ' '
            . $this->days[$this->repeatedDay]
            . ' of this month'
        );

        return $this;
    }
}
