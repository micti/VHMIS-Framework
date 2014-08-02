<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use Vhmis\I18n\DateTime\DateRepeat;

/**
 * DateTime repeat helper
 */
class Repeat extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'repeatByDay'   => 4,
        'repeatByMonth' => 4,
        'repeatByWeek'  => 4,
        'repeatByYear'  => 4
    );
    
    /**
     * Date repeat object
     * 
     * @var DateRepeat
     */
    protected $dateRepeat;

    public function __construct()
    {
        $this->dateRepeat = new DateRepeat;
    }

    /**
     * Find repeated dates by day
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $times
     * @param int $freq
     * @return array
     */
    public function repeatByDay($fromDate, $toDate, $times = 0, $freq = 1)
    {
        return $this->repeat($fromDate, $toDate, 4, $times, $freq);
    }

    /**
     * Find repeated dates by week
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $times
     * @param int $freq
     * @return array
     */
    public function repeatByWeek($fromDate, $toDate, $times = 0, $freq = 1) {
        return $this->repeat($fromDate, $toDate, 5, $times, $freq);
    }

    /**
     * Find repeated dates by month
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $times
     * @param int $freq
     * @return array
     */
    public function repeatByMonth($fromDate, $toDate, $times = 0, $freq = 1)
    {
        return $this->repeat($fromDate, $toDate, 6, $times, $freq);
    }

    /**
     * Find repeated dates by year
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $times
     * @param int $freq
     * @return array
     */
    public function repeatByYear($fromDate, $toDate, $times = 0, $freq = 1)
    {
        return $this->repeat($fromDate, $toDate, 7, $times, $freq);
    }

    /**
     * Find repeated dates
     *
     * @param string $fromDate
     * @param string $toDate
     * @param int $by
     * @param int $times
     * @param int $freq
     *
     * @return array
     */
    protected function repeat($fromDate, $toDate, $by, $times, $freq)
    {
        $rule = $this->dateRepeat->getRule();
        $rule->reset();
        $rule->setRepeatBy($by)->setBaseDate($this->getDateTimeObject());
        $rule->setFrequency($freq)->setRepeatTimes($times);
        return $this->dateRepeat->repeatedDates($fromDate, $toDate);
    }
}