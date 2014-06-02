<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n;

/**
 * DateTime
 */
class DateTime
{
    /**
     * Cache object for convert ...
     *
     * @var \IntlCalendar[]
     */
    protected static $cacheCalendars = array();

    /**
     * IntlCalendar object
     *
     * @var \IntlCalendar
     */
    protected $calendar;

    /**
     *
     * @var \DateTimeZone
     */
    protected $phpTimeZone;

    public function __construct($timezone = null, $calendar = null, $locale = null)
    {
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }
        $this->phpTimeZone = new \DateTimeZone($timezone);

        if ($calendar === null) {
            $calendar = 'gregorian';
        }

        if ($locale === null) {
            $locale = \Locale::getDefault();
        }

        $calendarLocale = $locale . '@calendar=' . $calendar;
        $this->calendar = \IntlCalendar::createInstance($this->phpTimeZone, $calendarLocale);

        if ($this->calendar === null) {
            return null;
        }
    }

    public function setDate($year, $month, $day)
    {
        $this->calendar->set($year, $month, $day);

        return $this;
    }

    public function set($time)
    {
        $time = new \DateTime($time, $this->phpTimeZone);

        if ($time === false) {
            return $this;
        }

        $mili = $time->getTimestamp() * 1000;
        $this->calendar->setTime($mili);

        return $this;
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
            return \IntlDateFormatter::formatObject($this->calendar, $formatMap[$format]);
        }

        return '';
    }

    public function formatISODate()
    {
        return \IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd');
    }

    public function formatISODateTime()
    {
        return \IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd HH:mm:ss');
    }

    /**
     *
     * @return string
     */
    public function getSecond()
    {
        return $this->format('s');
    }

    /**
     *
     * @return string
     */
    public function getMinute()
    {
        return $this->format('i');
    }

    /**
     *
     * @return string
     */
    public function getHour()
    {
        return $this->format('h');
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
        return $this->format('m');
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
        $this->calendar->add(\IntlCalendar::FIELD_SECOND, $amount);

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
        $this->calendar->add(\IntlCalendar::FIELD_MINUTE, $amount);

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
        $this->calendar->add(\IntlCalendar::FIELD_HOUR, $amount);

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
        $this->calendar->add(\IntlCalendar::FIELD_DAY_OF_MONTH, $amount);

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
        $this->calendar->add(\IntlCalendar::FIELD_MONTH, $amount);

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
        $this->calendar->add(\IntlCalendar::FIELD_YEAR, $amount);

        return $this;
    }

    public function convertTo($calendar)
    {
        $calendar = static::getCacheCalendar($calendar);
        $time = $this->calendar->getTime();
        $calendar->setTimeZone($this->phpTimeZone);
        $calendar->setTime($time);

        return \IntlDateFormatter::formatObject($calendar, 'yyyy-MM-dd');
    }

    protected static function getCacheCalendar($calendar)
    {
        if (isset(static::$cacheCalendars[$calendar])) {
            return static::$cacheCalendars[$calendar];
        }

        $locale = \Locale::getDefault() . '@calendar=' . $calendar;

        static::$cacheCalendars[$calendar] = \IntlCalendar::createInstance(null, $locale);

        return static::$cacheCalendars[$calendar];
    }
}
