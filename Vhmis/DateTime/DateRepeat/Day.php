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

        $run = clone $this->begin;

        if ($this->begin < $this->from) {
            $run->addDay(ceil($run->diffDay($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        while ($run <= $this->to) {
            $repeatedDates[] = $run->formatISODate();
            $run->addDay($this->ruleInfo['freq']);
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

        $date = new DateTime;
        $date->modify($this->ruleInfo['base'])->addDay(($this->ruleInfo['times'] - 1) * $this->ruleInfo['freq']);

        $this->ruleInfo['end'] = $date->formatISODate();

        return $this->ruleInfo['end'];
    }
}
