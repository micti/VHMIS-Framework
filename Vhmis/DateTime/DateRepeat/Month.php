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
    protected $repeatBy = 6;

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
     * Caculate all repeated dates in range
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return array
     */
    public function repeatedDates($fromDate, $toDate)
    {
        $repeatedDates = array();

        if ($this->ruleInfo === array()) {
            return $repeatedDates;
        }

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDates;
        }

        if ($this->ruleInfo['type'] === 'day') {
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
        $specialEndDate = $this->getSpecialEndDate();
        if ($specialEndDate !== false) {
            return $specialEndDate;
        }

        if ($this->ruleInfo['type'] === 'day') {
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
            $run->addMonth(ceil($run->diffMonth($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $month = (int) $run->getMonth();
        $day = $this->ruleInfo['days'][0];
        $run->setDay($day);
        $total = count($this->ruleInfo['days']);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISODate();
            }

            // Prevent run date goes to next month
            $dayPosition = array_search($day, $this->ruleInfo['days']);
            $run->setMonth($month)->setDay(1);

            if ($dayPosition === $total - 1) {
                $run->addMonth($this->ruleInfo['freq']);
                $month = (int) $run->getMonth();
                $day = $this->ruleInfo['days'][0];
            } else {
                $day = $this->ruleInfo['days'][($dayPosition + 1)];
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
            $run->addMonth(ceil($run->diffMonth($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
            $run->modify(
                $this->dayPositions[$this->ruleInfo['position']]
                . ' '
                . $this->days[$this->ruleInfo['day']]
                . ' of this month'
            );
        }

        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISODate();
            }

            // Prevent run date goes to next month
            $run->setDay(1)->addMonth($this->ruleInfo['freq']);
            $run->modify(
                $this->dayPositions[$this->ruleInfo['position']]
                . ' '
                . $this->days[$this->ruleInfo['day']]
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
        $date->modify($this->ruleInfo['base']);

        $position = array_search($this->ruleInfo['baseDay'], $this->ruleInfo['days']);
        if ($position !== 0) {
            $date->setDay($this->ruleInfo['days'][0]);
        }

        $repeatedMonth = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['days'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['days']);
        $endDayPosition = $mod === 0 ? count($this->ruleInfo['days']) - 1 : $mod - 1;
        $date->addMonth($repeatedMonth * $this->ruleInfo['freq']);

        if ($endDayPosition === 0) {
            $this->ruleInfo['end'] = $date->formatISODate();

            return $this->ruleInfo['end'];
        }

        $date->setDay($this->ruleInfo['days'][$endDayPosition]);
        $this->ruleInfo['end'] = $date->formatISODate();

        return $this->ruleInfo['end'];
    }

    /**
     * Caculate end date of repeat when type = relative_type
     *
     * @return string
     */
    protected function endDateByRelativeDayType()
    {
        $date = new DateTime;
        $date->modify($this->ruleInfo['base'])->setDay(1);

        $date->addMonth(($this->ruleInfo['times'] - 1) * $this->ruleInfo['freq']);
        $date->modify(
            $this->dayPositions[$this->ruleInfo['position']]
            . ' '
            . $this->days[$this->ruleInfo['day']]
            . ' of this month'
        );

        return $date->formatISODate();
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
                $repeatedDates[$i] = $date->formatISODate();
            }

            $fixDates[$repeatedDates[$i]] = $repeatedDates[$i];
        }

        return array_keys($fixDates);
    }
}
