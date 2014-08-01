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
 * Caculation repeated dates by day
 */
class Day extends AbstractRepeat
{
    protected $repeatBy = 4;

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

        if ($this->begin < $this->from) {
            $from = $this->date->createNewWithSameI18nInfo();
            $from->setTimestamp($this->from);
            $this->date->addDay(ceil($this->date->diffAbsoluteDay($from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        while ($this->date->getTimestamp() <= $this->to) {
            $repeatedDates[] = $this->date->getDateWithExtendedYear();
            $this->date->addDay($this->ruleInfo['freq']);
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

        $this->date->modify($this->ruleInfo['base'])->addDay(($this->ruleInfo['times'] - 1) * $this->ruleInfo['freq']);

        $this->ruleInfo['end'] = $this->date->getDateWithExtendedYear();

        return $this->ruleInfo['end'];
    }
}
