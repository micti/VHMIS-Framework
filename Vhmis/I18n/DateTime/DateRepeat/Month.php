<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\DateRepeat;

/**
 * Caculation repeated dates by month
 */
class Month extends AbstractRepeat
{
    protected $repeatBy = 6;

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

        $this->date->setTimestamp($this->begin)->setDay(1);

        // Skip some months
        if ($this->begin < $this->from) {
            $from = $this->date->createNewWithSameI18nInfo();
            $from->setTimestamp($this->from);
            $this->date->addMonth(ceil($this->date->diffAbsoluteMonth($from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $month = $this->date->getMonth();
        $day = $this->ruleInfo['days'][0];
        $this->date->setField(5, $day);
        $total = count($this->ruleInfo['days']);
        while ($this->date->getTimestamp() <= $this->to) {
            if ($this->date->getTimestamp() >= $this->begin && $this->date->getTimestamp() >= $this->from) {
                $repeatedDates[] = $this->date->getDateWithExtendedYear();
            }

            // Prevent run date goes to next month
            $dayPosition = array_search($day, $this->ruleInfo['days']);
            $this->date->setMonth($month)->setDay(1);

            if ($dayPosition === $total - 1) {
                $this->date->addMonth($this->ruleInfo['freq']);
                $month = $this->date->getMonth();
                $day = $this->ruleInfo['days'][0];
            } else {
                $day = $this->ruleInfo['days'][($dayPosition + 1)];
            }
            $this->date->setField(5, $day);
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

        $this->date->setTimestamp($this->begin);

        // Skip some months
        if ($this->begin < $this->from) {
            $from = $this->date->createNewWithSameI18nInfo();
            $from->setTimestamp($this->from);
            $this->date->setDay(1);
            $this->date->addMonth(ceil($this->date->diffAbsoluteMonth($from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
            $this->date->gotoNthDayOfMonth($this->ruleInfo['day'], $this->ruleInfo['position']);
        }

        while ($this->date->getTimestamp() <= $this->to) {
            if ($this->date->getTimestamp() >= $this->begin && $this->date->getTimestamp() >= $this->from) {
                $repeatedDates[] = $this->date->getDateWithExtendedYear();
            }

            // Prevent run date goes to next month
            $this->date->setDay(1)->addMonth($this->ruleInfo['freq']);
            $this->date->gotoNthDayOfMonth($this->ruleInfo['day'], $this->ruleInfo['position']);
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
        $this->date->setTimestamp($this->begin);

        $position = array_search($this->ruleInfo['baseDay'], $this->ruleInfo['days']);
        if ($position !== 0) {
            $this->date->setDay($this->ruleInfo['days'][0]);
        }

        $repeatedMonth = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['days'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['days']);
        $endDayPosition = $mod === 0 ? count($this->ruleInfo['days']) - 1 : $mod - 1;
        $this->date->addMonth($repeatedMonth * $this->ruleInfo['freq']);

        if ($endDayPosition === 0) {
            $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

            return $this->ruleInfo['end'];
        }

        // Allow over next month
        //$this->date->setDay($this->ruleInfo['days'][$endDayPosition]);
        $this->date->setField(5, $this->ruleInfo['days'][$endDayPosition]);
        $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

        return $this->ruleInfo['end'];
    }

    /**
     * Caculate end date of repeat when type = relative_type
     *
     * @return string
     */
    protected function endDateByRelativeDayType()
    {
        $this->date->setTimestamp($this->begin)->setDay(1);
        $this->date->addMonth(($this->ruleInfo['times'] - 1) * $this->ruleInfo['freq']);
        $this->date->gotoNthDayOfMonth($this->ruleInfo['day'], $this->ruleInfo['position']);

        return $this->date->getDateWithExtendedYear();
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
        $fixDates = array();
        for ($i = 0; $i < $total; $i++) {
            if (isset($fixDates[$repeatedDates[$i]])) {
                $this->date->modify(end($fixDates))->addDay(1);
                $repeatedDates[$i] = $this->date->getDateWithExtendedYear();
            }

            $fixDates[$repeatedDates[$i]] = $repeatedDates[$i];
        }

        return array_keys($fixDates);
    }
}
