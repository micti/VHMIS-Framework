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
class Month extends AbstractRepeat
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
     * Days in month for repeat
     *
     * @var int[]
     */
    protected $repeatedDays;

    /**
     * Day of repeated day
     *
     * @var int
     */
    protected $repeatedDay;

    /**
     * Set type of repeat
     *
     * - Repeat by day in month: 2nd, 3rd ....
     * - Repeat by relative day in month: first Monday, second Tuesday, last day ...
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
     * Set repeated days, use 1-31, accept int array or int string spec by ','
     * (for type = month)
     *
     * @param int[]|string $days
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     *
     * @throws \InvalidArgumentException
     */
    public function setRepeatedDays($days)
    {
        $days = is_string($days) ? explode(',', $days) : $days;

        // Check
        foreach ($days as &$day) {
            $day = (int) $day;
            if ($day < 0 || $day > 31) {
                throw new \InvalidArgumentException('Only int array or int string spec by `,`. From 1 - 31');
            }
        }

        $days = array_unique($days);
        sort($days);
        $this->repeatedDays = $days;

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

        if ($this->type === 'day') {
            return $this->repeatedDatesByDayType();
        }

        return $this->repeatedDatesByRelativeDayType();
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

        if ($this->type === 'day') {
            return $this->endDateByDayType();
        }

        return $this->endDateByRelativeDayType();
    }

    /**
     * Caculate all repeated dates when type = day
     *
     * @return array
     */
    protected function repeatedDatesByDayType()
    {
        $repeatedDates = array();

        $run = clone $this->begin;
        $run->setDay(1);

        // Skip some months
        if ($this->begin < $this->from) {
            $run->addMonth(ceil($run->diffMonth($this->from) / $this->freq) * $this->freq);
        }

        $month = (int) $run->getMonth();
        $day = $this->repeatedDays[0];
        $run->setDay($day);
        $total = count($this->repeatedDays);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISO(0);
            }

            // Prevent run date goes to next month
            $dayPosition = array_search($day, $this->repeatedDays);
            $run->setMonth($month)->setDay(1);

            if ($dayPosition === $total - 1) {
                $run->addMonth($this->freq);
                $month = (int) $run->getMonth();
                $day = $this->repeatedDays[0];
            } else {
                $day = $this->repeatedDays[($dayPosition + 1)];
            }
            $run->setDay($day);
        }

        return $this->fixSameRepeatedDates($repeatedDates);
    }

    /**
     * Caculate all repeated dates when type = relative_day
     *
     * @return array
     */
    protected function repeatedDatesByRelativeDayType()
    {
        $repeatedDates = array();

        $run = clone $this->begin;

        // Skip some months
        if ($this->begin < $this->from) {
            $run->setDay(1);
            $run->addMonth(ceil($run->diffMonth($this->from) / $this->freq) * $this->freq);
            $run->modify(
                $this->dayPositions[$this->repeatedDayPosition]
                . ' '
                . $this->days[$this->repeatedDay]
                . ' of this month'
            );
        }

        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISO(0);
            }

            // Prevent run date goes to next month
            $run->setDay(1)->addMonth($this->freq);
            $run->modify(
                $this->dayPositions[$this->repeatedDayPosition]
                . ' '
                . $this->days[$this->repeatedDay]
                . ' of this month'
            );
        }

        return $repeatedDates;
    }

    /**
     * Caculate end date of repeat when type = day
     *
     * @return string
     */
    protected function endDateByDayType()
    {
        $date = new DateTime;
        $date->modify($this->startDate);

        $position = array_search($this->startDay, $this->repeatedDays);

        if ($position !== 0) {
            $date->setDay($this->repeatedDays[0]);
        }

        $repeatedMonth = ceil(($this->repeatedTimes + $position) / count($this->repeatedDays)) - 1;
        $mod = ($this->repeatedTimes + $position) % count($this->repeatedDays);
        $endDayPosition = $mod === 0 ? count($this->repeatedDays) - 1 : $mod - 1;
        $date->addMonth($repeatedMonth * $this->freq);

        if ($endDayPosition === 0) {
            $this->endDate = $date->formatISO(0);

            return $this->endDate;
        }

        $date->setDay($this->repeatedDays[$endDayPosition]);
        $this->endDate = $date->formatISO(0);

        return $this->endDate;
    }

    /**
     * Caculate end date of repeat when type = relative_type
     *
     * @return string
     */
    protected function endDateByRelativeDayType()
    {
        $date = new DateTime;
        $date->modify($this->startDate)->setDay(1);

        $date->addMonth(($this->repeatedTimes - 1) * $this->freq);
        $date->modify(
            $this->dayPositions[$this->repeatedDayPosition]
            . ' '
            . $this->days[$this->repeatedDay]
            . ' of this month'
        );

        return $date->formatISO(0);
    }

    /**
     * Fixing same repeated dates in some special cases
     *
     * @param array $repeatedDates
     *
     * @return array
     */
    protected function fixSameRepeatedDates($repeatedDates)
    {
        $total = count($repeatedDates);
        $date = new DateTime;
        $fixDates = array();
        for ($i = 0; $i < $total; $i++) {
            if (isset($fixDates[$repeatedDates[$i]])) {
                $date->modify(end($fixDates))->addDay(1);
                $repeatedDates[$i] = $date->formatISO(0);
            }

            $fixDates[$repeatedDates[$i]] = $repeatedDates[$i];
        }

        return array_keys($fixDates);
    }
}
