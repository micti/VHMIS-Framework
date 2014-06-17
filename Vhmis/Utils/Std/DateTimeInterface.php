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
     * Get date, based on ISO format yyyy-mm-dd
     *
     * @return string
     */
    public function getDate();

    /**
     * Get time, based on ISO format hh:mm:ss
     *
     * @return string
     */
    public function getTime();

    /**
     * Get date and time, based on ISO format yyyy-mm-dd hh:mm:ss
     *
     * @return string
     */
    public function getDateTime();

    /**
     * Get epoch timestamp
     *
     * @retunr int
     */
    public function getTimestamp();

    /**
     * Object to string
     *
     * @return string
     */
    public function __toString();

    public function addSecond($amount);

    public function addMinute($amount);

    public function addHour($amount);

    public function addDay($amount);

    public function addWeek($amount);

    public function addMonth($amount);

    public function addYear($amount);

}
