<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils;

/**
 * Datetime funtions
 */
class DateTime
{
    /**
     *
     * @param string $string
     *
     * @return array
     */
    public static function praseDateTimeFormat($string)
    {
        $datetime = '/^(-?)(\d{1,5})-(\d{1,2})-(\d{1,2})(| (\d{1,2}):(\d{1,2}):(\d{1,2}))$/';
        $time = '/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/';
        $matches = array();
        $result = array();

        if (preg_match($datetime, $string, $matches)) {
            $result['date'] = array(
                'year'  => $matches[1] . $matches[2],
                'month' => $matches[3],
                'day'   => $matches[4]
            );

            if ($matches[5] !== '') {
                $result['time'] = array(
                    'hour'   => $matches[6],
                    'minute' => $matches[7],
                    'second' => $matches[8]
                );
            }

            return $result;
        }

        if (preg_match($time, $string, $matches)) {
            $result['time'] = array(
                'hour'   => $matches[1],
                'minute' => $matches[2],
                'second' => $matches[3]
            );
        }

        return $result;
    }

    /**
     * Sort weekday based on a first day
     * 1: Sunday -> 7: Saturday
     *
     * For example: if monday is start day of week, the return will be [2,3,4,5,6,7,1]
     *
     * @param int $firstDay
     *
     * @return array
     */
    public static function sortWeekday($firstDay)
    {
        $firstDay = (int) $firstDay;

        if ($firstDay < 1 || $firstDay > 7) {
            return array(1, 2, 3, 4, 5, 6, 7);
        }

        $weekdayOrder = array();

        for ($i = 1; $i <= 7; $i++) {
            if ($i >= $firstDay) {
                $weekdayOrder[$i - $firstDay] = $i;
            } else {
                $weekdayOrder[7 - $firstDay + $i] = $i;
            }
        }

        return $weekdayOrder;
    }

    /**
     * Get a list of weekdays (sorted based on a first day)
     * 1: Sunday -> 7: Saturday
     *
     * For example: if monday is start day of week, and list has 31 elements (like month has 31 days)
     * the return will be [2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4]
     *
     * @param int $firstDay
     * @param int $total
     *
     * @return array
     */
    public static function getSortedWeekdayList($firstDay, $total)
    {
        $firstDay = (int) $firstDay;
        $total = (int) $total;

        $sortedWeekday = self::sortWeekday($firstDay);
        $list = array();

        $j = 0;
        for ($i = 0; $i < $total; $i++) {
            $list[] = $sortedWeekday[$j];
            $j++;
            if ($j == 7) {
                $j = 0;
            }
        }

        return $list;
    }
}
