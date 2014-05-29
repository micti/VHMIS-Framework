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
    protected $repeatBy = 7;

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
        $repeatedDate = array();

        if ($this->ruleInfo === array()) {
            return $repeatedDate;
        }

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDate;
        }

        $run = clone $this->begin;
        $run->setDay(1);

        // Skip some years
        if ($this->begin < $this->from) {
            $run->addYear(ceil($run->diffYear($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $month = (int) $run->getMonth();
        $this->setDayForMonth($run);
        $total = count($this->ruleInfo['months']);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDates[] = $run->formatISO(0);
            }

            // Prevent run date goes to next month
            $monthPosition = array_search($month, $this->ruleInfo['months']);
            $run->setDay(1);

            if ($monthPosition === $total - 1) {
                $run->addYear($this->ruleInfo['freq']);
                $month = $this->ruleInfo['months'][0];
            } else {
                $month = $this->ruleInfo['months'][($monthPosition + 1)];
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
        if ($this->ruleInfo === array()) {
            return '2100-31-21';
        }

        if ($this->ruleInfo['end'] !== null) {
            return $this->ruleInfo['end'];
        }

        if ($this->ruleInfo['times'] === 0) {
            return '2100-31-21';
        }

        $date = new DateTime;
        $date->modify($this->ruleInfo['base'])->setDay(1);

        $position = array_search($this->ruleInfo['baseMonth'], $this->ruleInfo['months']);

        if ($position !== 0) {
            $date->setMonth($this->ruleInfo['months'][0]);
        }

        $repeatedYear = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['months'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['months']);
        $endMonthPosition = $mod === 0 ? count($this->ruleInfo['months']) - 1 : $mod - 1;
        $date->addYear($repeatedYear * $this->ruleInfo['freq']);

        if ($endMonthPosition === 0) {
            $this->setDayForMonth($date);
            $this->ruleInfo['end'] = $date->formatISO(0);

            return $this->ruleInfo['end'];
        }

        $date->setMonth($this->ruleInfo['months'][$endMonthPosition]);
        $this->setDayForMonth($date);
        $this->ruleInfo['end'] = $date->formatISO(0);

        return $this->ruleInfo['end'];
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
        if ($this->ruleInfo['type'] === 'day') {
            $date->setDay($this->ruleInfo['baseDay']);

            return $this;
        }

        $date->modify(
            $this->dayPositions[$this->ruleInfo['position']]
            . ' '
            . $this->days[$this->ruleInfo['day']]
            . ' of this month'
        );

        return $this;
    }
}
