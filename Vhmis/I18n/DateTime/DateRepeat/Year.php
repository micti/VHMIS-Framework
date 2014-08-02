<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\DateRepeat;

use Vhmis\I18n\DateTime\DateTime;

/**
 * Caculation repeated dates by month
 */
class Year extends AbstractRepeat
{
    protected $repeatBy = 7;

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

        $this->date->setTimestamp($this->begin);
        $this->date->setDay(1);

        // Skip some years
        if ($this->begin < $this->from) {
            $from = $this->date->createNewWithSameI18nInfo();
            $from->setTimestamp($this->from);
            $this->date->addYear(ceil($this->date->diffAbsoluteYear($from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $month = (int) $this->date->getMonth();
        $this->setDayForMonth($this->date);
        $total = count($this->ruleInfo['months']);
        while ($this->date->getTimestamp() <= $this->to) {
            if ($this->date->getTimestamp() >= $this->begin && $this->date->getTimestamp() >= $this->from) {
                $repeatedDates[] = $this->date->getDateWithExtendedYear();
            }

            // Prevent run date goes to next month
            $monthPosition = array_search($month, $this->ruleInfo['months']);
            $this->date->setDay(1);

            if ($monthPosition === $total - 1) {
                $this->date->addYear($this->ruleInfo['freq']);
                $month = $this->ruleInfo['months'][0];
            } else {
                $month = $this->ruleInfo['months'][($monthPosition + 1)];
            }
            $this->date->setMonth($month);
            $this->setDayForMonth($this->date);
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
        $specialEndDate = $this->getSpecialEndDate();
        if ($specialEndDate !== false) {
            return $specialEndDate;
        }

        $this->date->setTimestamp($this->begin);
        $this->date->setDay(1);

        $position = array_search($this->ruleInfo['baseMonth'], $this->ruleInfo['months']);

        if ($position !== 0) {
            $this->date->setMonth($this->ruleInfo['months'][0]);
        }

        $repeatedYear = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['months'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['months']);
        $endMonthPosition = $mod === 0 ? count($this->ruleInfo['months']) - 1 : $mod - 1;
        $this->date->addYear($repeatedYear * $this->ruleInfo['freq']);

        if ($endMonthPosition === 0) {
            $this->setDayForMonth($this->date);
            $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

            return $this->ruleInfo['end'];
        }

        $this->date->setMonth($this->ruleInfo['months'][$endMonthPosition]);
        $this->setDayForMonth($this->date);
        $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

        return $this->ruleInfo['end'];
    }

    /**
     * Set date for month based on type
     *
     * @param DateTime $date
     *
     * @return Year
     */
    protected function setDayForMonth($date)
    {
        if ($this->ruleInfo['type'] === 'day') {
            $date->setField(5, $this->ruleInfo['baseDay']);

            return $this;
        }

        $date->gotoNthDayOfMonth($this->ruleInfo['day'], $this->ruleInfo['position']);

        return $this;
    }
}
