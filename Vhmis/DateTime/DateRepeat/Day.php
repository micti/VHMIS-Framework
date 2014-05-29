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
        $repeatedDate = array();

        if ($this->ruleInfo === array()) {
            return $repeatedDate;
        }

        if ($this->checkRange($fromDate, $toDate) === false) {
            return $repeatedDate;
        }

        $run = clone $this->begin;

        if ($this->begin < $this->from) {
            $run->addDay(ceil($run->diffDay($this->from) / $this->ruleInfo['freq']) * $this->ruleInfo['freq']);
        }

        while ($run <= $this->to) {
            $repeatedDate[] = $run->formatISO(0);
            $run->addDay($this->ruleInfo['freq']);
        }

        return $repeatedDate;
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
        $date->modify($this->ruleInfo['base'])->addDay(($this->ruleInfo['times'] - 1) * $this->ruleInfo['freq']);

        $this->ruleInfo['end'] = $date->formatISO(0);

        return $this->ruleInfo['end'];
    }
}
