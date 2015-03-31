<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime;

use \Vhmis\Utils\Std\DateTimeInterface;
use \Vhmis\Utils\Std\AbstractDateTime;
use \Vhmis\Utils\DateTime as DateTimeUtil;
use \Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * I18n Datetime class
 */
class SimpleDateTime extends AbstractDateTime implements DateTimeInterface
{

    /**
     * List supported calendars
     *
     * @var array
     */
    public static $calendars = array(
        'gregorian' => 'gregorian',
        'chinese' => 'chinese',
        'coptic' => 'coptic',
        'dangi' => 'dangi',
        'ethiopic' => 'ethiopic',
        'hebrew' => 'hebrew',
        'indian' => 'indian',
        'islamic-civil' => 'islamic-civil',
        'islamic' => 'islamic',
        'japanese' => 'japanese',
        'persian' => 'persian',
        'taiwan' => 'taiwan',
        'buddhist' => 'buddhist'
    );

    /**
     * IntlCalendar object
     *
     * @var \IntlCalendar
     */
    protected $calendar;

    /**
     * Construct
     *
     * @param mixed  $timezone
     * @param string $calendar
     * @param string $locale
     *
     * @throws InvalidArgumentException
     */
    public function __construct($timezone = null, $calendar = null, $locale = null)
    {
        if (!isset(static::$calendars[$calendar])) {
            $calendar = 'gregorian';
        }

        if ($locale === null) {
            $locale = \Locale::getDefault();
        }

        $locale = $locale . '@calendar=' . $calendar;

        $this->calendar = \IntlCalendar::createInstance($timezone, $locale);

        if ($this->calendar === null) {
            throw new InvalidArgumentException('Timezone is not valid.');
        }
    }

    /**
     * Set date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return SimpleDateTime
     */
    public function setDate($year, $month, $day)
    {
        $month = (int) $month - 1;
        $this->calendar->set(\IntlCalendar::FIELD_IS_LEAP_MONTH, 0);
        $this->calendar->set((int) $year, $month, (int) $day);

        return $this;
    }

    /**
     * Set date with extended year.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return SimpleDateTime
     */
    public function setDateWithExtenedYear($year, $month, $day)
    {
        $month = (int) $month - 1;

        $this->calendar->set(\IntlCalendar::FIELD_IS_LEAP_MONTH, 0);
        $this->calendar->set(\IntlCalendar::FIELD_EXTENDED_YEAR, (int) $year);
        $this->calendar->set(\IntlCalendar::FIELD_MONTH, $month);
        $this->calendar->set(\IntlCalendar::FIELD_DAY_OF_MONTH, (int) $day);

        return $this;
    }

    /**
     * Set time
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $millisecond
     *
     * @return SimpleDateTime
     */
    public function setTime($hour, $minute, $second = 0, $millisecond = 0)
    {
        $this->calendar->set(\IntlCalendar::FIELD_HOUR_OF_DAY, (int) $hour);
        $this->calendar->set(\IntlCalendar::FIELD_MINUTE, (int) $minute);
        $this->calendar->set(\IntlCalendar::FIELD_SECOND, (int) $second);
        $this->calendar->set(\IntlCalendar::FIELD_MILLISECOND, (int) $millisecond);

        return $this;
    }

    /**
     * Set epoch timestamp (UTC 00:00)
     *
     * @param int $timestamp
     *
     * @return SimpleDateTime
     */
    public function setTimestamp($timestamp)
    {
        $this->calendar->setTime((int) $timestamp * 1000);

        return $this;
    }

    /**
     * Set milliseconds since the epoch
     *
     * @param float $milliseconds
     *
     * @return SimpleDateTime
     */
    public function setMilliTimestamp($milliseconds)
    {
        $this->calendar->setTime($milliseconds);

        return $this;
    }

    /**
     * Set timezone
     *
     * @param mixed $timeZone
     *
     * @return SimpleDateTime
     */
    public function setTimeZone($timeZone)
    {
        $this->calendar->setTimeZone($timeZone);

        return $this;
    }

    /**
     * Get timezone name
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->calendar->getTimeZone()->getDisplayName();
    }

    /**
     * Get calendar type
     *
     * @return string
     */
    public function getType()
    {
        return $this->calendar->getType();
    }

    /**
     * Get milliseconds since the epoch
     *
     * @return float
     */
    public function getMilliTimestamp()
    {
        return $this->calendar->getTime();
    }

    /**
     * Get epoch timestamp (UTC 00:00)
     *
     * @return int
     */
    public function getTimestamp()
    {
        return (int) ($this->calendar->getTime() / 1000);
    }

    /**
     * Format
     *
     * @param string|array|int $format
     *
     * @return string
     */
    public function format($format, $locale = '')
    {
        if ($locale === '') {
            $locale = $this->getLocale();
        }
        
        return \IntlDateFormatter::formatObject($this->calendar, $format, $locale);
    }

    /**
     * Get value of field
     *
     * @param int $field
     *
     * @return int
     */
    public function getField($field)
    {
        $value = $this->calendar->get($field);

        if ($field === \IntlCalendar::FIELD_MONTH) {
            $value++;
        }

        return $value;
    }

    /**
     * Set value of field in min and max range of field
     *
     * @param int $field
     * @param int $value
     *
     * @return boolean
     */
    public function setField($field, $value)
    {
        $value = (int) $value;

        if ($field === \IntlCalendar::FIELD_MONTH) {
            $value--;
        }

        $max = $this->calendar->getMaximum($field);
        $min = $this->calendar->getMinimum($field);

        if ($value < $min || $value > $max) {
            return false;
        }

        return $this->calendar->set($field, $value);
    }

    /**
     * Add or sub value of field
     *
     * @param int $field
     * @param int $amount
     *
     * @return SimpleDateTime
     */
    public function addField($field, $amount)
    {
        $amount = (int) $amount;
        $this->calendar->add($field, $amount);

        return $this;
    }

    /**
     * Calculate difference between given time and this object's time
     *
     * @param SimpleDateTime $date
     * @param int            $field
     *
     * @return int
     */
    public function diffField($date, $field)
    {
        $milli = $date->getMilliTimestamp();

        return $this->calendar->fieldDifference($milli, $field);
    }

    /**
     * Get weekday that is first day of week
     * 1 : Sunday -> 7 : Saturday
     *
     * @return int
     */
    public function getWeekFirstDay()
    {
        return $this->calendar->getFirstDayOfWeek();
    }

    /**
     * Get sorted weekday based on first day of week
     *
     * @return int[]
     */
    public function getSortedWeekday()
    {
        return DateTimeUtil::sortedWeekday($this->getWeekFirstDay());
    }

    /**
     * Set weekday that is first day of week
     * 1 : Sunday -> 7 : Saturday
     *
     * @param int $dayOfWeek
     *
     * @return SimpleDateTime
     */
    public function setWeekFirstDay($dayOfWeek)
    {
        $this->calendar->setFirstDayOfWeek($dayOfWeek);

        return $this;
    }

    /**
     * Get locale.
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->calendar->getLocale(1);
    }

    /**
     * Object to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('yyyy-MM-dd HH:mm:ss');
    }

    /**
     * New datetime with same I18n info
     *
     * @return SimpleDateTime
     */
    public function createNewWithSameI18nInfo()
    {
        $calendar = $this->getType();
        $locale = $this->calendar->getLocale(1);
        $timezone = $this->calendar->getTimeZone();

        $new = new static($timezone, $calendar, $locale);

        $new->setWeekFirstDay($this->getWeekFirstDay());
        $new->setMilliTimestamp($this->getMilliTimestamp());

        return $new;
    }

    /**
     * Clone object
     *
     * @return SimpleDateTime
     */
    public function __clone()
    {
        $this->helpers = array();
        $this->calendar = clone $this->calendar;
    }
}
