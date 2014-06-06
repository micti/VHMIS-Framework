<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils\Std;

/**
 * DateTime abstract
 */
abstract class AbstractDateTime
{
    const FIELD_SECOND = 0;
    const FIELD_MINUTE = 1;
    const FIELD_HOUR = 2;
    const FIELD_DAY = 3;
    const FIELD_WEEK = 4;
    const FIELD_MONTH = 5;
    const FIELD_YEAR = 6;

    /**
     *
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     *
     * @return boolean
     */
    protected function isValidFieldValue($value, $min, $max)
    {
        if ($value < $min || $value > $max) {
            return false;
        }

        return true;
    }
}
