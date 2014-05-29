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
    protected $repeatBy = 5;

    /**
     * Set rule
     *
     * @param \Vhmis\DateTime\DateRepeat\Rule $rule
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     */
    public function setRule(Rule $rule)
    {
        parent::setRule($rule);

        if ($this->ruleInfo !== array()) {
            $this->ruleInfo['weekdays'] = $this->sortRepeatedWeekday($this->ruleInfo['weekdays']);
        }

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

        if ($this->ruleInfo === array()) {
            return $repeatedDate;
        }

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDate;
        }

        $run = clone $this->begin;

        // Skip some weeks
        if ($this->begin < $this->from) {
            $run->addWeek(ceil($run->diffWeek($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $run->modifyThisWeek($this->weekday[$this->ruleInfo['weekdays'][0]]);

        $total = count($this->ruleInfo['weekdays']);
        while ($run <= $this->to) {
            if ($run >= $this->begin && $run >= $this->from) {
                $repeatedDate[] = $run->formatISO(0);
            }

            $day = array_search($run->format('w'), $this->ruleInfo['weekdays']);

            if ($day === $total - 1) {
                $run->addWeek($this->ruleInfo['freq']);
                $run->modifyThisWeek($this->weekday[$this->ruleInfo['weekdays'][0]]);
            } else {
                $run->modifyThisWeek($this->weekday[$this->ruleInfo['weekdays'][($day + 1)]]);
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
        $specialEndDate = $this->getSpecialEndDate();
        if ($specialEndDate !== false) {
            return $specialEndDate;
        }

        $date = new DateTime;
        $date->modify($this->ruleInfo['base']);

        $position = array_search($this->ruleInfo['baseWeekday'], $this->ruleInfo['weekdays']);

        if ($position !== 0) {
            $date->modify('previous ' . $this->weekday[$this->ruleInfo['weekdays'][0]]);
        }

        $repeatedWeek = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['weekdays'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['weekdays']);
        $endDayPosition = $mod === 0 ? count($this->ruleInfo['weekdays']) - 1 : $mod - 1;
        $date->addWeek($repeatedWeek * $this->ruleInfo['freq']);

        if ($endDayPosition === 0) {
            $this->ruleInfo['end'] = $date->formatISO(0);

            return $this->ruleInfo['end'];
        }

        $date->modify('next ' . $this->weekday[$this->ruleInfo['weekdays'][$endDayPosition]]);
        $this->ruleInfo['end'] = $date->formatISO(0);

        return $this->ruleInfo['end'];
    }

    /**
     * Range repeated days based on start day of week
     *
     * @param array $weekdays
     *
     * @return array
     */
    protected function sortRepeatedWeekday($weekdays)
    {
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

        return array_unique(array_merge($before, $after));
    }
}
