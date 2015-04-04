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
     * Prase datetime string.
     * 
     * @param string $string
     *
     * @return array
     */
    public static function praseFormat($string)
    {
        $format = [
            'Year',
            'YearMonth',
            'DateTime',
            'Time'
        ];
        
        foreach($format as $type) {
            $result = self::{'prase' . $type . 'Format'}($string);
            
            if($result !== []) {
                return $result;
            }
        }
        
        return [];
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
    public static function sortedWeekday($firstDay)
    {
        $firstDay = (int) $firstDay;

        if ($firstDay < 1 || $firstDay > 7) {
            return array(1, 2, 3, 4, 5, 6, 7);
        }

        $result = array(
            1 => array(1, 2, 3, 4, 5, 6, 7),
            2 => array(2, 3, 4, 5, 6, 7, 1),
            3 => array(3, 4, 5, 6, 7, 1, 2),
            4 => array(4, 5, 6, 7, 1, 2, 3),
            5 => array(5, 6, 7, 1, 2, 3, 4),
            6 => array(6, 7, 1, 2, 3, 4, 5),
            7 => array(7, 1, 2, 3, 4, 5, 6)
        );

        /*$weekdayOrder = array();

        for ($i = 1; $i <= 7; $i++) {
            if ($i >= $firstDay) {
                $weekdayOrder[$i - $firstDay] = $i;
            } else {
                $weekdayOrder[7 - $firstDay + $i] = $i;
            }
        }

        return $weekdayOrder;*/

        return $result[$firstDay];
    }

    /**
     * Sort weekdays based on first day of week
     * 2, [1, 3, 5, 6] -> [3, 5, 6, 1]
     *
     * @param int   $firstDay
     * @param int[] $weekdays
     *
     * @return array
     */
    public static function sortWeekday($firstDay, $weekdays)
    {
        sort($weekdays);
        $before = array();
        $after = array();
        $total = count($weekdays);

        for ($i = 0; $i < $total; $i++) {
            if ($firstDay <= $weekdays[$i]) {
                $before[] = $weekdays[$i];
            } else {
                $after[] = $weekdays[$i];
            }
        }

        return array_unique(array_merge($before, $after));
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

        $sortedWeekday = self::sortedWeekday($firstDay);
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

    /**
     * Get position of weekdays in sorted weekday list (based-0)
     *
     * Sorted weekday list [2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4,5,6,7,1,2,3,4]
     * Get position of weekday 7,1 [5,6,12,13,19,20,26,27]
     *
     * @param array $weekdays
     * @param array $sortedWeekdayList
     *
     * @return int
     */
    public static function getPositionOfWeekdayFromSortedWeekdayList($weekdays, $sortedWeekdayList)
    {
        $count = count($sortedWeekdayList);
        $result = array();
        for ($i = 0; $i < $count ; $i++) {
            if (in_array($sortedWeekdayList[$i], $weekdays)) {
                $result[] = $i;
            }
        }

        return $result;
    }
    
    protected static function praseYearFormat($string)
    {
        $pattern = '/^(-?)(\d{1,5})$/';
        $matches = array();
        $result = array();

        if (preg_match($pattern, $string, $matches)) {
            $result['date'] = array(
                'year'  => $matches[1] . $matches[2],
                'month' => 1,
                'day'   => 1
            );
        }
        
        return $result;
    }
    
    protected static function praseYearMonthFormat($string)
    {
        $pattern = '/^(-?)(\d{1,5})-(\d{1,2})$/';
        $matches = array();
        $result = array();

        if (preg_match($pattern, $string, $matches)) {
            $result['date'] = array(
                'year'  => $matches[1] . $matches[2],
                'month' => $matches[3],
                'day'   => 1
            );
        }
        
        return $result;
    }
    
    protected static function praseDateTimeFormat($string)
    {
        $pattern = '/^(-?)(\d{1,5})-(\d{1,2})-(\d{1,2})(| (\d{1,2}):(\d{1,2}):(\d{1,2}))$/';
        $matches = array();
        $result = array();

        if (preg_match($pattern, $string, $matches)) {
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
        }
        
        return $result;
    }
    
    protected static function praseTimeFormat($string)
    {
        $pattern = '/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/';
        $matches = array();
        $result = array();

        if (preg_match($pattern, $string, $matches)) {
            $result['time'] = array(
                'hour'   => $matches[1],
                'minute' => $matches[2],
                'second' => $matches[3]
            );
        }
        
        return $result;
    }
}
