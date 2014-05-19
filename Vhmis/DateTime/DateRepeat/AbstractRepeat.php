<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime\DateRepeat;

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
     * Construct
     *
     * @param string      $startDate
     * @param string|null $endDate
     * @param int         $times
     * @param int         $freq
     */
    public function __construct($startDate, $endDate, $times, $freq)
    {
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
     * Caculate all repeated dates
     *
     * @param string|null $fromDate
     * @param string|null $toDate
     *
     * @return array
     */
    abstract public function repeatedDates($fromDate = null, $toDate = null);

    /**
     * Caculate end date of repeat
     *
     * @return string
     */
    abstract public function endDate();
}
