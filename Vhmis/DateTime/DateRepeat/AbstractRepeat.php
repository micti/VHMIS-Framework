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
    protected $startDate;
    protected $repeatedTimes;
    protected $freq;
    protected $endDate;

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
     * Weekday of start date (0 - 6)
     *
     * @var int
     */
    protected $startWeekday;

    /**
     * Start day of week
     *
     * @var string
     */
    protected $startDayOfWeek = 'monday';

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
     *
     * @param string      $startDate
     * @param string|null $endDate
     * @param int         $times
     * @param int         $freq
     */
    public function __construct($startDate, $endDate, $times, $freq)
    {
        $this->to = new DateTime;
        $this->from = new DateTime;
        $this->begin = new DateTime;
        $this->end = new DateTime;

        $this->to->setTime(0, 0, 0);
        $this->from->setTime(0, 0, 0);
        $this->begin->setTime(0, 0, 0);
        $this->end->setTime(0, 0, 0);

        $this->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setFreq($freq)
            ->setRepeatTimes($times);
    }

    /**
     *
     * @param string $startDate
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        $this->begin->modify($startDate);
        $this->startWeekday = (int) $this->begin->format('w');

        return $this;
    }

    /**
     *
     * @param string|null $endDate
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        if ($this->endDate !== null) {
            $this->end->modify($endDate);
        }

        return $this;
    }

    /**
     *
     * @param int $times
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
     */
    public function setRepeatTimes($times)
    {
        $this->repeatedTimes = (int) $times;

        return $this;
    }

    /**
     *
     * @param int $freq
     *
     * @return \Vhmis\DateTime\DateRepeat\AbstractRepeat
     */
    public function setFreq($freq)
    {
        $this->freq = (int) $freq;

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
        if (array_search($day, $this->weekday) !== false) {
            $this->startDayOfWeek = $day;

            $this->begin->setStartDayOfWeek($day);
            $this->end->setStartDayOfWeek($day);
            $this->from->setStartDayOfWeek($day);
            $this->to->setStartDayOfWeek($day);

            return $this;
        }

        if (isset($this->weekday[$day])) {
            $this->startDayOfWeek = $this->weekday[$day];

            $this->begin->setStartDayOfWeek($this->weekday[$day]);
            $this->end->setStartDayOfWeek($this->weekday[$day]);
            $this->from->setStartDayOfWeek($this->weekday[$day]);
            $this->to->setStartDayOfWeek($this->weekday[$day]);

            return $this;
        }

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
        $this->begin->modify($this->startDate);
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
            $this->to->modify($this->end->formatISO(0));
        }

        return true;
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
