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
use Vhmis\Utils\DateTime as DateTimeUtil;

/**
 * Abstract class for all DateRepeat classes
 */
abstract class AbstractRepeat
{
    protected $repeatBy;

    /**
     * Datetime object, use for caculating ...
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Rule info
     *
     * @var array
     */
    protected $ruleInfo = array();

    /**
     * Timestamp begin
     *
     * @var int
     */
    protected $begin = 0;

    /**
     * Timestamp from
     *
     * @var int
     */
    protected $from = 0;

    /**
     * Timestamp to
     *
     * @var int
     */
    protected $to = 0;

    /**
     * Timestamp end
     *
     * @var int
     */
    protected $end = 0;

    /**
     * Set rule of repeat
     *
     * @param Rule $rule
     *
     * @return AbstractRepeat
     */
    public function setRule(Rule $rule)
    {
        if (!$rule->isValid()) {
            $this->ruleInfo = array();

            return $this;
        }

        $info = $rule->getInfo();
        if ($info['by'] !== $this->repeatBy) {
            $this->ruleInfo = array();

            return $this;
        }

        $this->ruleInfo = $info;

        $this->ruleInfo['weekdays'] = DateTimeUtil::sortWeekday(
            $info['date']->getWeekFirstDay(),
            $this->ruleInfo['weekdays']
        );

        $this->date = $info['date']->createNewWithSameI18nInfo();

        $this->to = 0;
        $this->from = 0;
        $this->begin = $info['date']->getTimestamp();
        $this->end = 0;

        return $this;
    }

    /**
     * Check range
     * Return false if range is out start date and end date
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return boolean
     */
    protected function checkRange($fromDate, $toDate)
    {
        $this->from = $this->date->modify($fromDate)->getTimestamp();
        $this->to = $this->date->modify($toDate)->getTimestamp();
        $this->end = $this->date->modify($this->endDate())->getTimestamp();

        if ($this->begin > $this->to) {
            return false;
        }

        if ($this->from > $this->end) {
            return false;
        }

        if ($this->to > $this->end) {
            $this->to = $this->end;
        }

        return true;
    }

    /**
     * Find special end date, otherwise return false
     *
     * @return string
     */
    protected function getSpecialEndDate()
    {
        if ($this->ruleInfo === array()) {
            return '2100-12-31';
        }

        if ($this->ruleInfo['end'] !== null) {
            return $this->ruleInfo['end'];
        }

        if ($this->ruleInfo['times'] === 0) {
            return '2100-12-31';
        }

        return false;
    }

    /**
     * Caculate all repeated dates in range
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return array
     */
    abstract public function repeatedDates($fromDate, $toDate);

    /**
     * Caculate end date of repeat
     *
     * @return string
     */
    abstract public function endDate();
}
