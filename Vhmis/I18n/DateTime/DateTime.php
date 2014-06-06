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
use \Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * I18n Datetime class
 *
 * Support many calendars with locale info
 */
class DateTime implements DateTimeInterface
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
     *
     * @var \DateTimeZone
     */
    protected $phpTimeZone;

    /**
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
     * @return null if failure
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
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return DateTime
     */
    public function setDate($year, $month, $day)
    {
        $this->calendar->set($year, $month, $day);

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
        $this->calendar->set(\IntlCalendar::FIELD_HOUR_OF_DAY, $hour);
        $this->calendar->set(\IntlCalendar::FIELD_MINUTE, $minute);
        $this->calendar->set(\IntlCalendar::FIELD_SECOND, $second);

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
     * Get epoch timestamp (UTC 00:00)
     *
     * @return int
     */
    public function getTimestamp()
    {
        return (int) ($this->calendar->getTime() / 1000);
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
}
