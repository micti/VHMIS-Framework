<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime;

/**
 * Datetime Helper class
 */
class Helper
{
    /**
     * Compare 2 times hh:mm
     *
     * @param string $time1
     * @param string $time2
     *
     * @return int
     */
    public static function compareTime($time1, $time2)
    {
        list($hour1, $min1) = explode(':', $time1, 2);
        $hour1 = (int) $hour1;
        $min1 = (int) $min1;

        list($hour2, $min2) = explode(':', $time2, 2);
        $hour2 = (int) $hour2;
        $min2 = (int) $min2;

        if ($hour1 > $hour2) {
            return 1;
        }

        if ($hour2 > $hour1) {
            return -1;
        }

        if ($min1 > $min2) {
            return 1;
        }

        if ($min2 > $min1) {
            return -1;
        }

        return 0;
    }
}
