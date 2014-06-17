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
use \Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * I18n Datetime class
 *
 * Support many calendars with locale info
 */
class DateTime extends AbstractDateTime implements DateTimeInterface
{
    /**
     * List supported calendars
     *
     * @var array
     */
    public static $calendars = array(
        'gregorian'     => 'gregorian',
        'chinese'       => 'chinese',
        'coptic'        => 'coptic',
        'dangi'         => 'dangi',
        'ethiopic'      => 'ethiopic',
        'hebrew'        => 'hebrew',
        'indian'        => 'indian',
        'islamic-civil' => 'islamic-civil',
        'islamic'       => 'islamic',
        'japanese'      => 'japanese',
        'persian'       => 'persian',
        'taiwan'        => 'taiwan',
        'buddhist'      => 'buddhist'
    );

    /**
     * IntlCalendar object
     *
     * @var \IntlCalendar
     */
    protected $calendar;

    /**
     * Helper namespace
     *
     * @var string
     */
    protected $helperNamespace = '\Vhmis\I18n\DateTime\Helper';

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
        $calendar = $this->getCalendarType($calendar);

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
     * Set date
     * Month based-1
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return DateTime
     */
    public function setDate($year, $month, $day)
    {
        $month = (int) $month - 1;
        $this->calendar->set((int) $year, $month, (int) $day);

        return $this;
    }

    /**
     * Set time
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return DateTime
     */
    public function setTime($hour, $minute, $second)
    {
        $this->calendar->set(\IntlCalendar::FIELD_HOUR_OF_DAY, (int) $hour);
        $this->calendar->set(\IntlCalendar::FIELD_MINUTE, (int) $minute);
        $this->calendar->set(\IntlCalendar::FIELD_SECOND, (int) $second);

        return $this;
    }

    /**
     * Set timezone
     *
     * @param mixed $timeZone
     *
     * @return \Vhmis\I18n\DateTime\DateTime
     */
    public function setTimeZone($timeZone)
    {
        $this->calendar->setTimeZone($timeZone);

        return $this;
    }

    /**
     * Set epoch timestamp (UTC 00:00)
     *
     * @param int $timestamp
     *
     * @return DateTime
     */
    public function setTimestamp($timestamp)
    {
        $this->calendar->setTime((int) $timestamp * 1000);

        return $this;
    }

    /**
     * Get date (based on ISO format yyyy-mm-dd)
     *
     * @return string
     */
    public function getDate()
    {
        $year = str_pad($this->calendar->get(\IntlCalendar::FIELD_YEAR), 4, '0', STR_PAD_LEFT);
        $month = str_pad($this->calendar->get(\IntlCalendar::FIELD_MONTH) + 1, 2, '0', STR_PAD_LEFT);
        $day = str_pad($this->calendar->get(\IntlCalendar::FIELD_DAY_OF_MONTH), 2, '0', STR_PAD_LEFT);

        $date = $year . '-' . $month . '-' . $day;
        //$date = \IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd');
        return $date;
    }

    /**
     * Get date (based on ISO format hh:mm:ss)
     *
     * @return string
     */
    public function getTime()
    {
        $hour = str_pad($this->calendar->get(\IntlCalendar::FIELD_HOUR_OF_DAY), 2, '0', STR_PAD_LEFT);
        $minute = str_pad($this->calendar->get(\IntlCalendar::FIELD_MINUTE), 2, '0', STR_PAD_LEFT);
        $second = str_pad($this->calendar->get(\IntlCalendar::FIELD_SECOND), 2, '0', STR_PAD_LEFT);

        $time = $hour . ':' . $minute . ':' . $second;
        //$time = \IntlDateFormatter::formatObject($this->calendar, 'HH:mm:ss');
        return $time;
    }

    /**
     * Get date and time (based on ISO format yyyy-mm-dd hh:mm:ss)
     *
     * @return string
     */
    public function getDateTime()
    {
        $dateTime = $this->getDate() . ' ' . $this->getTime();
        //$dateTime = \IntlDateFormatter::formatObject($this->calendar, 'yyyy-MM-dd HH:mm:ss');
        return $dateTime;
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
     * Get epoch timestamp (UTC 00:00)
     *
     * @return int
     */
    public function getTimestamp()
    {
        return (int) ($this->calendar->getTime() / 1000);
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
     * Object to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getDateTime();
    }

    public function __get($name)
    {
        return $this->getHelper($name);
    }

    /**
     * Add second
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addSecond($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_SECOND, $amount);

        return $this;
    }

    /**
     * Add minute
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addMinute($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_MINUTE, $amount);

        return $this;
    }

    /**
     * Add hour
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addHour($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_HOUR, $amount);

        return $this;
    }

    /**
     * Add day
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addDay($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_DAY_OF_MONTH, $amount);

        return $this;
    }

    /**
     * Add week
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addWeek($amount)
    {
        $amount = (int) $amount;
        $this->addDay($amount * 7);

        return $this;
    }

    /**
     * Add month
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addMonth($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_MONTH, $amount);

        return $this;
    }

    /**
     * Add year
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addYear($amount)
    {
        $amount = (int) $amount;
        $this->calendar->add(\IntlCalendar::FIELD_YEAR, $amount);

        return $this;
    }

    /**
     * Set second
     *
     * @param int $second
     *
     * @return DateTime
     */
    public function setSecond($second)
    {
        return $this->set(\IntlCalendar::FIELD_SECOND, $second);
    }

    /**
     * Set minute
     *
     * @param int $minute
     *
     * @return DateTime
     */
    public function setMinute($minute)
    {
        return $this->set(\IntlCalendar::FIELD_MINUTE, $minute);
    }

    /**
     * Set hour
     *
     * @param int $hour
     *
     * @return DateTime
     */
    public function setHour($hour)
    {
        return $this->set(\IntlCalendar::FIELD_HOUR_OF_DAY, $hour);
    }

    /**
     * Set day
     *
     * @param int $day
     *
     * @return DateTime
     */
    public function setDay($day)
    {
        return $this->set(\IntlCalendar::FIELD_DAY_OF_MONTH, $day);
    }

    /**
     * Set month (1-based)
     *
     * @param int $month
     *
     * @return DateTime
     */
    public function setMonth($month)
    {
        $month = (int) $month - 1;
        $max = $this->calendar->getActualMaximum(\IntlCalendar::FIELD_MONTH);
        $min = $this->calendar->getActualMinimum(\IntlCalendar::FIELD_MONTH);

        if ($this->isValidFieldValue($month, $min, $max)) {
            $currentMonth = $this->calendar->get(\IntlCalendar::FIELD_MONTH);
            $month = $month - $currentMonth;
            $this->calendar->add(\IntlCalendar::FIELD_MONTH, $month);
            $this->calendar->set(\IntlCalendar::FIELD_IS_LEAP_MONTH, 0);
        }

        return $this;
    }

    /**
     * Set leap month (1-based)
     *
     * @param int $month
     *
     * @return DateTime
     */
    public function setLeapMonth($month)
    {
        $currentMonth = $this->calendar->get(\IntlCalendar::FIELD_MONTH);
        $currentDay = $this->calendar->get(\IntlCalendar::FIELD_DAY_OF_MONTH);
        $isLeapCurrentMonth = $this->calendar->get(\IntlCalendar::FIELD_IS_LEAP_MONTH);

        $month = (int) $month - 1;
        $max = $this->calendar->getActualMaximum(\IntlCalendar::FIELD_MONTH);
        $min = $this->calendar->getActualMinimum(\IntlCalendar::FIELD_MONTH);

        if ($this->isValidFieldValue($month, $min, $max)) {
            $month = $month - $currentMonth + 1; // for leap
            $this->calendar->add(\IntlCalendar::FIELD_MONTH, $month);

            if ($this->calendar->get(\IntlCalendar::FIELD_IS_LEAP_MONTH) !== 1) {
                $this->calendar->set(\IntlCalendar::FIELD_MONTH, $currentMonth);
                $this->calendar->set(\IntlCalendar::FIELD_DAY_OF_MONTH, $currentDay);
                $this->calendar->set(\IntlCalendar::FIELD_IS_LEAP_MONTH, $isLeapCurrentMonth);
            }
        }

        return $this;
    }

    /**
     * Set year
     *
     * @param int $year
     *
     * @return DateTime
     */
    public function setYear($year)
    {
        $year = (int) $year;
        $max = $this->calendar->getActualMaximum(\IntlCalendar::FIELD_YEAR);
        $min = $this->calendar->getActualMinimum(\IntlCalendar::FIELD_YEAR);

        if ($this->isValidFieldValue($year, $min, $max)) {
            $currentYear = $this->calendar->get(\IntlCalendar::FIELD_YEAR);
            $year = $year - $currentYear;
            $this->calendar->add(\IntlCalendar::FIELD_YEAR, $year);
        }

        return $this;
    }

    /**
     * Set extended year
     *
     * @param int $year
     *
     * @return DateTime
     */
    public function setExtendedYear($year)
    {
        return $this->set(\IntlCalendar::FIELD_EXTENDED_YEAR, $year);
    }

    /**
     * Get Gregorian related year from other calendar year
     *
     * @param string $calendar
     *
     * @return int
     */
    public function getGregorianRelatedYear()
    {
        $year = $this->calendar->get(\IntlCalendar::FIELD_EXTENDED_YEAR);
        $calendar = $this->getType();

        switch ($calendar) {
            case 'persian':
                $year += 622;
                break;
            case 'hebrew':
                $year -= 3760;
                break;
            case 'chinese':
                $year -= 2637;
                break;
            case 'indian':
                $year += 79;
                break;
            case 'coptic':
                $year += 284;
                break;
            case 'ethiopic':
                $year += 8;
                break;
            case 'dangi':
                $year -= 2333;
                break;
            case 'islamic':
            case 'islamic-civil':
                $year = $this->islamicYearToGregorianYear(year);
                break;
            default:
                break;
        }

        return $year;
    }

    /**
     * Set value of field
     *
     * @param int $field
     * @param int $value
     *
     * @return \Vhmis\I18n\DateTime\DateTime
     */
    protected function set($field, $value)
    {
        $value = (int) $value;
        $max = $this->calendar->getActualMaximum($field);
        $min = $this->calendar->getActualMinimum($field);

        if ($this->isValidFieldValue($value, $min, $max)) {
            $this->calendar->set($field, $value);
        }

        return $this;
    }

    /**
     * Get calendar
     *
     * @param string $calendar
     *
     * @return string
     */
    protected function getCalendarType($calendar)
    {
        if (!isset(static::$calendars[$calendar])) {
            $calendar = 'gregorian';
        }

        return $calendar;
    }

    /**
     * Get Gregorian related year from Islamic calendar year
     *
     * @param int $islamicYear
     *
     * @return int
     */
    protected function islamicYearToGregorianYear($islamicYear)
    {
        $cycle = $offset = $shift = 0;

        if ($islamicYear >= 1397) {
            $cycle = ($islamicYear - 1397) / 67;
            $offset = ($islamicYear - 1397) % 67;
            $shift = 2 * $cycle + (($offset >= 33) ? 1 : 0);
        } else {
            $cycle = ($islamicYear - 1396) / 67 - 1;
            $offset = -($islamicYear - 1396) % 67;
            $shift = 2 * $cycle + (($offset <= 33) ? 1 : 0);
        }

        return $islamicYear + 579 - $shift;
    }
}
