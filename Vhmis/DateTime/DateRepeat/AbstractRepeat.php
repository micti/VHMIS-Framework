<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime\DateRepeat;

use Vhmis\DateTime\DateTime;

/**
 * Abstract class for all DateRepeat classes
 */
abstract class AbstractRepeat
{
    protected $repeatBy;

    /**
     * Rule info
     *
     * @var array
     */
    protected $ruleInfo = array();

    /**
     * Datetime object helpers
     *
     * @var DateTime
     */
    protected $begin;

    /**
     * Datetime object helpers
     *
     * @var DateTime
     */
    protected $from;

    /**
     * Datetime object helpers
     *
     * @var DateTime
     */
    protected $to;

    /**
     * Datetime object helpers
     *
     * @var DateTime
     */
    protected $end;

    /**
     * Start day of week
     *
     * @var string
     */
    protected $startOfWeek = 1;

    /**
     * Weekday in english
     *
     * @var array
     */
    protected $weekday = array(
        '0' => 'sunday',
        '1' => 'monday',
        '2' => 'tuesday',
        '3' => 'wednesday',
        '4' => 'thursday',
        '5' => 'friday',
        '6' => 'saturday'
    );

    /**
     * Construct
     */
    public function __construct()
    {
        $this->to = new DateTime;
        $this->from = new DateTime;
        $this->begin = new DateTime;
        $this->end = new DateTime;

        $this->to->setTime(0, 0, 0);
        $this->from->setTime(0, 0, 0);
        $this->begin->setTime(0, 0, 0);
        $this->end->setTime(0, 0, 0);
    }

    /**
     * Set rule of repeat
     *
     * @param \Vhmis\DateTime\DateRepeat\Rule $rule
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
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
        $this->begin->modify($this->ruleInfo['base']);

        return $this;
    }

    /**
     * Set start day of week
     *
     * @param string $day
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
     */
    public function setStartDayOfWeek($day)
    {
        $this->begin->setStartOfWeek($day);
        $this->end->setStartOfWeek($day);
        $this->from->setStartOfWeek($day);
        $this->to->setStartOfWeek($day);
        $this->startOfWeek = $this->begin->getStartOfWeek();

        return $this;
    }

    /**
     * Check range
     * Return false if range is out start date and end date
     *
     * @param  string  $fromDate
     * @param  string  $toDate
     * @return boolean
     */
    protected function checkRange($fromDate, $toDate)
    {
        $this->from->modify($fromDate);
        $this->end->modify($this->endDate());
        $this->to->modify($toDate);

        if ($this->begin > $this->to) {
            return false;
        }

        if ($this->from > $this->end) {
            return false;
        }

        if ($this->to > $this->end) {
            $this->to->modify($this->end->formatISODate());
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
