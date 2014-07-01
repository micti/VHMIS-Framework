<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\Utils\Std\AbstractDateTimeHelper;
use \Vhmis\Utils\DateTime as DateTimeUtils;
use \Vhmis\I18n\DateTime\DateTime;

/**
 * DateTime set helper
 */
class Set extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'setMillisecond'     => 1,
        'setSecond'          => 1,
        'setMinute'          => 1,
        'setHour'            => 1,
        'setDay'             => 1,
        'setIsLeapMonth'     => 1,
        'setMonth'           => 1,
        'setLeapMonth'       => 1,
        'setYear'            => 1,
        'setEra'             => 1,
        'setNow'             => 0,
        'setNextDay'         => 0,
        'setPreviousDay'     => 0,
        'setTomorrow'        => 0,
        'setYesterday'       => 0,
        'setFirstDayOfMonth' => 0,
        'setLastDayOfMonth'  => 0
    );

    /**
     * Set millisecond
     *
     * @param int $millisecond
     *
     * @return DateTime
     */
    public function setMillisecond($millisecond)
    {
        $this->date->setField(14, $millisecond);

        return $this->date;
    }

    /**
     * Set second
     *
     * @param int $second
     *
     * @return DateTime
     */
    public function setSecond($second)
    {
        $this->date->setField(13, $second);

        return $this->date;
    }

    /**
     * Set minute
     *
     * @param int $minute
     *
     * @return DateTime
     */
    public function setMinute($minute)
    {
        $this->date->setField(12, $minute);

        return $this->date;
    }

    /**
     * Set hour
     *
     * @param int $hour
     *
     * @return DateTime
     */
    public function setHour($hour)
    {
        $this->date->setField(11, $hour);

        return $this->date;
    }

    /**
     * Set day
     *
     * @param int $day
     *
     * @return DateTime
     */
    public function setDay($day)
    {
        $month = $this->date->getField(2);
        $year = $this->date->getField(1);

        $this->date->setField(5, $day);

        return $this->fix($year, $month);
    }

    /**
     * Set is leap month
     *
     * @param int $leap
     *
     * @return DateTime
     */
    public function setIsLeapMonth($leap)
    {
        $this->date->setField(22, $leap);

        return $this->date;
    }

    /**
     * Set month
     *
     * @param int $month
     *
     * @return DateTime
     */
    public function setMonth($month)
    {
        $year = $this->date->getField(1);

        if (!$this->date->setField(2, $month)) {
            return $this->date;
        }

        return $this->fix($year, $month);
    }

    /**
     * Set leap month
     *
     * @param int $month
     *
     * @return DateTime
     */
    public function setLeapMonth($month)
    {
        $year = $this->date->getField(1);
        $currentMonth = $this->date->getField(2);
        $day = $this->date->getField(5);
        $isLeap = $this->date->getField(22);

        $this->setMonth($month);
        $this->date->addField(2, 1);

        if ($this->date->getField(22) !== 1) {
            $this->setYear($year);
            $this->setMonth($currentMonth);
            $this->setDay($day);
            $this->setIsLeapMonth($isLeap);
        }

        // Todo: fix day
        // $day = $this->date->getActualMaximumValueOfField(5);
        return $this->date;
    }

    /**
     * Set year
     *
     * @param int $year
     *
     * @return DateTime
     */
    public function setYear($year)
    {
        $month = $this->date->getField(2);

        if (!$this->date->setField(1, $year)) {
            return $this->date;
        }

        return $this->fix($year, $month);
    }

    /**
     * Set era
     *
     * @param int $era
     *
     * @return DateTime
     */
    public function setEra($era)
    {
        $month = $this->date->getField(2);
        $year = $this->date->getField(1);

        $this->date->setField(0, $era);

        return $this->fix($year, $month);
    }

    /**
     * Set now
     *
     * @return DateTime
     */
    public function setNow()
    {
        $this->date->setMilliTimestamp(\IntlCalendar::getNow());

        return $this->date;
    }

    /**
     * Set previous day
     *
     * @return DateTime
     */
    public function setPreviousDay()
    {
        $this->date->addField(5, -1);

        return $this->date;
    }

    /**
     * Set next day
     *
     * @return DateTime
     */
    public function setNextDay()
    {
        $this->date->addField(5, 1);

        return $this->date;
    }

    /**
     * Set yesterday
     *
     * @return DateTime
     */
    public function setYesterday()
    {
        $this->setNow();
        $this->setPreviousDay();

        return $this->date;
    }

    /**
     * Set tomorrow
     *
     * @return DateTime
     */
    public function setTomorrow()
    {
        $this->setNow();
        $this->setNextDay();

        return $this->date;
    }

    /**
     * Set first day of month
     *
     * @return DateTime
     */
    public function setFirstDayOfMonth()
    {
        $this->date->setField(5, 1);

        return $this->date;
    }

    /**
     * Set last day of month
     *
     * @return DateTime
     */
    public function setLastDayOfMonth()
    {
        $max = $this->date->getMaximumValueOfField(5);
        $this->date->setField(5, $max['actual']);

        return $this->date;
    }

    /**
     * Set first day of week
     *
     * @return DateTime
     */
    public function setFirstDayOfWeek()
    {
        $sortedWeekdays = DateTimeUtils::sortWeekday($this->date->getWeekFirstDay());
        $position = array_search($this->date->getField(7), $sortedWeekdays);
        $this->date->addField(5, 0 - $position);

        return $this->date;
    }

    /**
     * Set last day of week
     *
     * @return DateTime
     */
    public function setLastDayOfWeek()
    {
        $sortedWeekdays = DateTimeUtils::sortWeekday($this->date->getWeekFirstDay());
        $position = array_search($this->date->getField(7), $sortedWeekdays);
        $this->date->addField(5, 6 - $position);

        return $this->date;
    }

    /**
     * Fix day
     *
     * @param int $year
     * @param int $month
     *
     * @return DateTime
     */
    protected function fix($year, $month)
    {
        $this->date->setField(22, 0);

        if ($month !== $this->date->getField(2)) {
            $this->date->setField(5, 1); // move first day
            $this->date->setField(1, $year);
            $this->date->setField(2, $month);

            $max = $this->date->getMaximumValueOfField(5);
            $this->date->setField(5, $max['actual']);
        }

        return $this->date;
    }
}
