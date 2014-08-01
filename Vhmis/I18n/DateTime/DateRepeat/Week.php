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
 * Caculation repeated dates by week
 */
class Week extends AbstractRepeat
{
    protected $repeatBy = 5;

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
        $repeatedDates = array();

        if ($this->ruleInfo === array()) {
            return $repeatedDates;
        }

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDates;
        }

        $this->date->setTimestamp($this->begin);

        // Skip some weeks
        if ($this->begin < $this->from) {
            $from = $this->date->createNewWithSameI18nInfo();
            $from->setTimestamp($this->from);
            $this->date->addWeek(ceil($this->date->diffAbsoluteWeek($from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        $this->date->gotoDayOfWeek($this->ruleInfo['weekdays'][0]);

        $total = count($this->ruleInfo['weekdays']);
        while ($this->date->getTimestamp() <= $this->to) {
            if ($this->date->getTimestamp() >= $this->begin && $this->date->getTimestamp() >= $this->from) {
                $repeatedDates[] = $this->date->getDateWithExtendedYear();
            }

            $day = array_search($this->date->getField(7), $this->ruleInfo['weekdays']);

            if ($day === $total - 1) {
                $this->date->addWeek($this->ruleInfo['freq']);
                $this->date->gotoDayOfWeek($this->ruleInfo['weekdays'][0]);
            } else {
                $this->date->gotoDayOfWeek($this->ruleInfo['weekdays'][($day + 1)]);
            }
        }

        return $repeatedDates;
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

        $this->date->setTimestamp($this->begin);

        $position = array_search($this->ruleInfo['baseWeekday'], $this->ruleInfo['weekdays']);

        if ($position !== 0) {
            $this->date->gotoDayOfWeek($this->ruleInfo['weekdays'][0]);
        }

        $repeatedWeek = ceil(($this->ruleInfo['times'] + $position) / count($this->ruleInfo['weekdays'])) - 1;
        $mod = ($this->ruleInfo['times'] + $position) % count($this->ruleInfo['weekdays']);
        $endDayPosition = $mod === 0 ? count($this->ruleInfo['weekdays']) - 1 : $mod - 1;
        $this->date->addWeek($repeatedWeek * $this->ruleInfo['freq']);

        if ($endDayPosition === 0) {
            $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

            return $this->ruleInfo['end'];
        }

        $this->date->gotoDayOfWeek($this->ruleInfo['weekdays'][$endDayPosition]);
        $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

        return $this->ruleInfo['end'];
    }
}
