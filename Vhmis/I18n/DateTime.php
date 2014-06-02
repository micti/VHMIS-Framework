<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n;

use \IntlCalendar;
use \IntlDateFormatter;
use \Locale;

/**
 * DateTime
 */
class DateTime
{
    /**
     * IntlCalendar object
     *
     * @var IntlCalendar
     */
    protected $calendar;

    public function __construct($timezone = null, $calendar = null, $locale = null)
    {
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }
        $timezone = new \DateTimeZone($timezone);

        if ($calendar === null) {
            $calendar = 'gregorian';
        }

        if ($locale === null) {
            $locale = Locale::getDefault();
        }

        $calendarLocale = $locale . '@calendar=' . $calendar;
        $this->calendar = IntlCalendar::createInstance($timezone, $calendarLocale);

        if ($this->calendar === null) {
            return null;
        }
    }

    public function set($time)
    {
        $timezone = $this->calendar->getTimeZone()->toDateTimeZone();
        $time = new \DateTime($time, $timezone);

        if ($time === false) {
            return $this;
        }

        $mili = $time->getTimestamp() * 1000;
        $this->calendar->setTime($mili);

        return $this;
    }

    public function formatISODate()
    {
        return IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd');
    }

    public function formatISODateTime()
    {
        return IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd HH:mm:ss');
    }

    /**
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format)
    {
        $formatMap = array(
            'd' => 'dd',
            'Y' => 'yyyy',
            'm' => 'MM',
            'h' => 'HH',
            'i' => 'mm',
            's' => 'ss'
        );

        if (isset($formatMap[$format])) {
            return IntlDateFormatter::formatObject($this->calendar, $formatMap[$format]);
        }

        return '';
    }

    /**
     *
     * @return string
     */
    public function getDay()
    {
        return $this->format('d');
    }

    /**
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->format('d');
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return $this->format('Y');
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addSecond($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_SECOND, $amount);

        return $this;
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addMinute($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_MINUTE, $amount);

        return $this;
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addHour($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_HOUR, $amount);

        return $this;
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addDay($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_DAY_OF_MONTH, $amount);

        return $this;
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addMonth($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_MONTH, $amount);

        return $this;
    }

    /**
     *
     * @param int $amount
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function addYear($amount)
    {
        $this->calendar->add(IntlCalendar::FIELD_YEAR, $amount);

        return $this;
    }
}
