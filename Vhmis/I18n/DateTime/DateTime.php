<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime;

use \Vhmis\Utils\DateTime as DateTimeUtils;
use \Vhmis\Utils\Std\DateTimeInterface;
use \Vhmis\Utils\Std\AbstractDateTime;
use \Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * I18n Datetime class
 *
 * Support many calendars with locale info
 *
 * @method \Vhmis\I18n\DateTime\DateTime setMillisecond(int $millisecond) Set millisecond
 * @method \Vhmis\I18n\DateTime\DateTime setSecond(int $second) Set second
 * @method \Vhmis\I18n\DateTime\DateTime setMinute(int $minute) Set minute
 * @method \Vhmis\I18n\DateTime\DateTime setHour(int $hour) Set hour
 * @method \Vhmis\I18n\DateTime\DateTime setDay(int $day) Set day
 * @method \Vhmis\I18n\DateTime\DateTime setIsLeapMont(int $day) Set isLeapMonth
 * @method \Vhmis\I18n\DateTime\DateTime setMonth(int $month) Set month
 * @method \Vhmis\I18n\DateTime\DateTime setLeapMonth(int $month) Set leap month
 * @method \Vhmis\I18n\DateTime\DateTime setYear(int $year) Set year
 * @method \Vhmis\I18n\DateTime\DateTime setEra(int $era) Set era
 * @method \Vhmis\I18n\DateTime\DateTime setNow() Set now
 * @method \Vhmis\I18n\DateTime\DateTime setPreviousDay() Set previous day
 * @method \Vhmis\I18n\DateTime\DateTime setNextDay() Set next day
 * @method \Vhmis\I18n\DateTime\DateTime setTomorrow() Set tomorrow
 * @method \Vhmis\I18n\DateTime\DateTime setYesterday() Set yesterday
 *
 * @method \Vhmis\I18n\DateTime\DateTime addMillisecond(int $amount) Add millisecond
 * @method \Vhmis\I18n\DateTime\DateTime addSecond(int $amount) Add second
 * @method \Vhmis\I18n\DateTime\DateTime addMinute(int $amount) Add minute
 * @method \Vhmis\I18n\DateTime\DateTime addHour(int $amount) Add hour
 * @method \Vhmis\I18n\DateTime\DateTime addDay(int $amount) Add day
 * @method \Vhmis\I18n\DateTime\DateTime addWeek(int $amount) Add week
 * @method \Vhmis\I18n\DateTime\DateTime addMonth(int $amount) Add month
 * @method \Vhmis\I18n\DateTime\DateTime addYear(int $amount) Add year
 * @method \Vhmis\I18n\DateTime\DateTime addEra(int $amount) Add era
 *
 * @method string getDate() Get date as ISO style
 * @method string getDateWithExtendedYear() Get date with extended year as ISO style
 * @method string getDateWithRelatedYear() Get date with related year as ISO style
 * @method string getTime() Get time as ISO style
 * @method string getDateTime() Get datetime as ISO style
 * @method string getDateTimeWithExtendedYear() Get datetime with extended year as ISO style
 * @method string getDateTimeWithRelatedYear() Get datetime with related year as ISO style
 * @method int getMillsecond() Get second
 * @method int getSecond() Get second
 * @method int getMinute() Get minute
 * @method int getHour() Get hour
 * @method int getDay() Get day
 * @method int getWeek() Get week
 * @method int getMonth() Get month
 * @method int getYear() Get year
 * @method int getEra() Get era
 *
 * @method string formatFull() Format datetime in full style
 * @method string formatLong() Format datetime in long style
 * @method string formatMedium() Format datetime in medium style
 * @method string formatShort() Format datetime in short style
 *
 * @method array diff(\Vhmis\I18n\DateTime\DateTime $datetime) Get different
 * @method int diffEra(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by era
 * @method int diffYear(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by year
 * @method int diffMonth(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by month
 * @method int diffDay(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by day
 * @method int diffHour(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by hour
 * @method int diffMinute(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by minute
 * @method int diffSecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by second
 * @method int diffMillisecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by millisecond
 * @method array diffAbsolute(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different
 * @method int diffAbsoluteEra(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by era
 * @method int diffAbsoluteYear(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by year
 * @method int diffAbsoluteMonth(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by month
 * @method int diffAbsoluteDay(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by day
 * @method int diffAbsoluteHour(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by hour
 * @method int diffAbsoluteMinute(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by minute
 * @method int diffAbsoluteSecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by second
 * @method double diffAbsoluteMillisecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by millisecond
 *
 * @property-read \Vhmis\I18n\DateTime\Helper\Convert $convert Convert helper
 * @property-read \Vhmis\I18n\DateTime\Helper\RelatedYear $relatedYear Related year helper
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
        $month = (int) $month - 1;
        $this->calendar->set((int) $year, $month, (int) $day);

        return $this;
    }

    /**
     * Set date with year is extended year
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return DateTime
     */
    public function setDateWithExtenedYear($year, $month, $day)
    {
        $month = (int) $month - 1;
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
     * @return DateTime
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
     * Set date or/and time by ISO style datetime
     * Year is extended year
     *
     * @param string $string
     *
     * @return DateTime
     */
    public function modify($string)
    {
        $result = DateTimeUtils::praseDateTimeFormat($string);

        if (isset($result['date'])) {
            $this->setDateWithExtenedYear(
                (int) $result['date']['year'], (int) $result['date']['month'], (int) $result['date']['day']);
        }

        if (isset($result['time'])) {
            $this->setTime(
                (int) $result['time']['hour'], (int) $result['time']['minute'], (int) $result['time']['second']
            );
        }

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
     * Set milliseconds since the epoch
     *
     * @param float $milliseconds
     *
     * @return DateTime
     */
    public function setMilliTimestamp($milliseconds)
    {
        $this->calendar->setTime($milliseconds);

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
     * Get epoch timestamp (UTC 00:00)
     *
     * @return int
     */
    public function getTimestamp()
    {
        return (int) ($this->calendar->getTime() / 1000);
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
     * Get calendar type
     *
     * @return string
     */
    public function getType()
    {
        return $this->calendar->getType();
    }

    /**
     * Format
     *
     * @param string|array|int $format
     *
     * @return string
     */
    public function format($format)
    {
        return \IntlDateFormatter::formatObject($this->calendar, $format, $this->calendar->getLocale(1));
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
     * @return DateTime
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
     * @param DateTime $date
     * @param int      $field
     *
     * @return int
     */
    public function diffField($date, $field)
    {
        $milli = $date->getMilliTimestamp();

        return $this->calendar->fieldDifference($milli, $field);
    }

    /**
     * Get maximum values of field
     * - Greatest : greatest maxium
     * - Least : least maxium
     * - Actual : maxium based on current date/time values
     *
     * @param int $field
     *
     * @return array
     */
    public function getMaximumValueOfField($field)
    {
        return array(
            'greatest' => $this->calendar->getMaximum($field),
            'actual'  => $this->calendar->getActualMaximum($field),
            'least'   => $this->calendar->getLeastMaximum($field)
        );
    }

    /**
     * Get minimum values of field
     * - Greatest : greatest minimum
     * - Least : least minimum
     * - Actual : minimum based on current date/time values
     *
     * @param int $field
     *
     * @return array
     */
    public function getMinimumValueOfField($field)
    {
        return array(
            'least'  => $this->calendar->getMinimum($field),
            'actual'   => $this->calendar->getActualMinimum($field),
            'greatest' => $this->calendar->getGreatestMinimum($field)
        );
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

    /**
     * Magic __call method for some helpers
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $helperName = array('add', 'set', 'get', 'format', 'diff');

        foreach ($helperName as $helper) {
            if (strpos($name, $helper) === 0) {
                $helper = $this->getHelper($helper);

                return $helper($name, $arguments);
            }
        }

        return null;
    }

    /**
     * magic __get method for get helper object
     *
     * @param string $name
     *
     * @return \Vhmis\Utils\Std\AbstractDateTimeHelper
     */
    public function __get($name)
    {
        return $this->getHelper($name);
    }
}
