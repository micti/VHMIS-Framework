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
 * DateTime interface
 */
interface DateTimeInterface
{
    /**
     * Set date
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return DateTimeInterface
     */
    public function setDate($year, $month, $day);

    /**
     * Set time
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return DateTimeInterface
     */
    public function setTime($hour, $minute, $second);

    /**
     * Set epoch timestamp
     *
     * @param int $timestamp
     *
     * @return DateTimeInterface
     */
    public function setTimestamp($timestamp);

    /**
     * Object to string
     *
     * @return string
     */
    public function __toString();

}
