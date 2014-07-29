<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\Utils\DateTime as DateTimeUtils;
use \Vhmis\I18n\DateTime\DateTime;

/**
 * DateTime set helper
 */
class Go extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'gotoNextDay'         => 0,
        'gotoPreviousDay'     => 0,
        'gotoTomorrow'        => 0,
        'gotoYesterday'       => 0,
        'gotoDayOfWeek'       => 1,
        'gotoFirstDayOfWeek'  => 0,
        'gotoLastDayOfWeek'   => 0,
        'gotoFirstDayOfMonth' => 0,
        'gotoLastDayOfMonth'  => 0,
        'gotoNthDayOfMonth'   => 2
    );

    /**
     * Go to previous day
     *
     * @return DateTime
     */
    public function gotoPreviousDay()
    {
        $this->date->addField(5, -1);

        return $this->date;
    }

    /**
     * Go to next day
     *
     * @return DateTime
     */
    public function gotoNextDay()
    {
        $this->date->addField(5, 1);

        return $this->date;
    }

    /**
     * Go to yesterday
     *
     * @return DateTime
     */
    public function gotoYesterday()
    {
        $this->date->setNow();
        $this->gotoPreviousDay();

        return $this->date;
    }

    /**
     * Go to tomorrow
     *
     * @return DateTime
     */
    public function gotoTomorrow()
    {
        $this->date->setNow();
        $this->gotoNextDay();

        return $this->date;
    }

    /**
     * Go to first day of month
     *
     * @return DateTime
     */
    public function gotoFirstDayOfMonth()
    {
        $this->date->setField(5, 1);

        return $this->date;
    }

    /**
     * Set last day of month
     *
     * @return DateTime
     */
    public function gotoLastDayOfMonth()
    {
        $max = $this->date->getMaximumValueOfField(5);
        $this->date->setField(5, $max['actual']);

        return $this->date;
    }

    /**
     * Set day of week
     *
     * @param int $weekday
     *
     * @return DateTime
     */
    public function gotoDayOfWeek($weekday)
    {
        $weekday = (int) $weekday;
        if ($weekday < 1 || $weekday > 7) {
            return $this->date;
        }

        $currentWeekday = $this->date->getField(7);
        $postion = $this->date->getDayOfWeekPosition();
        $amount = $postion[$weekday] - $postion[$currentWeekday];
        $this->date->addField(5, $amount);

        return $this->date;
    }

    /**
     * Set first day of week
     *
     * @return DateTime
     */
    public function gotoFirstDayOfWeek()
    {
        $sortedWeekdays = $this->date->getSortedWeekday();

        return $this->gotoDayOfWeek($sortedWeekdays[0]);
    }

    /**
     * Set last day of week
     *
     * @return DateTime
     */
    public function gotoLastDayOfWeek()
    {
        $sortedWeekdays = $this->date->getSortedWeekday();

        return $this->gotoDayOfWeek($sortedWeekdays[6]);
    }

    /**
     * Go to Nth day of month
     *
     * Type of day
     * - Day in month 0
     * - Weekday (Sunday to Saturday) 1 - 7
     * - WorkingDay 8
     * - Weekend 9
     *
     * @param int $type
     * @param int $nth
     *
     * @return DateTime
     */
    public function gotoNthDayOfMonth($type, $nth)
    {
        $nth = (int) $nth;
        $type = (int) $type;

        // Data
        $dayOfWeekType = $this->date->getDayOfWeekType();
        $maxium = $this->date->getMaximumValueOfField(5);

        // Wrong value of params, nothing changes
        if (!isset($dayOfWeekType[$type]) || $nth == 0) {
            return $this->date;
        }

        // Move to first day of month and get weekday list based on first day
        $this->date->setField(5, 1);
        $sortedWeekdayList = DateTimeUtils::getSortedWeekdayList($this->date->getField(7), $maxium['actual']);

        // Get weekday list based on type
        $list = $dayOfWeekType[$type];
        if ($type > 0 && $type < 8) {
            $list = array($type);
        }

        // Get all position of days of type
        $positions = DateTimeUtils::getPositionOfWeekdayFromSortedWeekdayList($list, $sortedWeekdayList);

        // Reversed Nth
        if ($nth < 0) {
            $nth *= -1;
            $positions = array_reverse($positions);
        }

        // Nth is out range, get maxium
        if ($nth > count($positions)) {
            $nth = count($positions);
        }

        // Set Nth day
        $day = $positions[$nth - 1] + 1;
        $this->date->setField(5, $day);

        return $this->date;
    }
}
