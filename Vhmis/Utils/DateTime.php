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
     * Sort weekday based on week's first day
     * 1: Sunday -> 7: Saturday
     *
     * For example: if monday is start day of week, the return will be [2,3,4,5,6,7,1]
     *
     * @param int $weekFirstDay
     *
     * @return array
     */
    public static function sortWeekday($weekFirstDay)
    {
        $weekFirstDay = (int) $weekFirstDay;

        if ($weekFirstDay < 1 || $weekFirstDay > 7) {
            return array(1, 2, 3, 4, 5, 6, 7);
        }

        $weekdayOrder = array();

        for ($i = 1; $i <= 7; $i++) {
            if ($i >= $weekFirstDay) {
                $weekdayOrder[$i - $weekFirstDay] = $i;
            } else {
                $weekdayOrder[7 - $weekFirstDay + $i] = $i;
            }
        }

        return $weekdayOrder;
    }
}
