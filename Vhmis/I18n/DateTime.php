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
 * I18n dateTime class
 *
 * @method string getMonth() Get month of date (2 characters)
 * @method string getYear() Get year of date (4 characters)
 * @method string getDay() Get day of date (2 characters)
 * @method string getHour() Get month of date (2 characters)
 * @method string getMinute() Get year of date (2 characters)
 * @method string getSecond() Get day of date (2 characters)
 * @method string formatISODate() Format date as ISO date format (Y-m-d)
 * @method string formatISODateTime() Format date as ISO datetime format (Y-m-d H:i:s)
 * @method string formatISOYearMonth() Format date as ISO year and month format (Y-m),
 * @method string formatSQLDate() Format date as SQL date format
 * @method string formatSQLDateTime() Format date as SQL datetime format
 */
class DateTime
{
    /**
     * Start year, to caculate related year
     *
     * @see http://cldr.unicode.org/development/development-process/design-proposals/pattern-character-for-related-year
     *
     * @var array
     */
    public static $startYear = array(
        'chinese' => -2636,
        'dangi'   => -2332
    );

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
     * Cache objects for convert ...
     *
     * @var \IntlCalendar[]
     */
    protected static $cachedCalendars = array();

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

    protected $methods = array(
        'getDay' => array('format', 'd'),
        'getMonth' => array('format', 'm'),
        'getYear' => array('format', 'Y'),
        'getSecond' =>  array('format', 's'),
        'getMinute' =>  array('format', 'i'),
        'getHour' =>  array('format', 'h'),
        'formatISODate' => array('format', 'isodate'),
        'formatISODateTime' => array('format', 'isodatetime'),
        'formatISOYearMonth' => array('format', 'isoyearmonth'),
        'formatSQLDate' => array('formatISODate'),
        'formatSQLDateTime' => array('formatISODateTime'),
    );

    /**
     * Construct
     *
     * @param mixed  $timezone
     * @param string $calendar
     * @param string $locale
     */
    public function __construct($timezone = null, $calendar = null, $locale = null)
    {
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }
        $this->phpTimeZone = new \DateTimeZone($timezone);

        if (!isset(static::$calendars[$calendar])) {
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

    /**
     * Magic for set/get/format method
     *
     * @param string $name
     * @param mixed  $arguments
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function __call($name, $arguments)
    {
        if (isset($this->methods[$name])) {
            $method = $this->methods[$name][0];
            $arguments = isset($this->methods[$name][1]) ? $this->methods[$name][1] : $arguments;

            return $this->$method($arguments);
        }

        return $this;
    }

    /**
     * Set date
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return \Vhmis\I18n\DateTime
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
     * @return \Vhmis\I18n\DateTime
     */
    public function setTime($hour, $minute, $second)
    {
        $this->calendar->set(\IntlCalendar::FIELD_HOUR_OF_DAY, $hour);
        $this->calendar->set(\IntlCalendar::FIELD_MINUTE, $minute);
        $this->calendar->set(\IntlCalendar::FIELD_SECOND, $second);

        return $this;
    }

    /**
     * Get unix timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return (int) $this->calendar->getTime() / 1000;
    }

    /**
     * Set unix timestamp
     *
     * @param int $time
     *
     * @return \Vhmis\I18n\DateTime
     */
    public function setTimestamp($time)
    {
        $time = (int) $time * 1000;
        $this->calendar->setTime($time);

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
            's' => 'ss',
            'isodate' => 'yyyy-MM-dd',
            'isoyearmonth' => 'yyyy-MM',
            'isodatetime' => 'yyyy-MM-dd HH:mm:ss'
        );

        if (isset($formatMap[$format])) {
            return \IntlDateFormatter::formatObject($this->calendar, $formatMap[$format]);
        }

        return '';
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

    /**
     * Convert date to others calendar
     *
     * @param string $calendar
     *
     * @return array
     */
    public function convertTo($calendar)
    {
        $result = array();

        if (!isset(static::$calendars[$calendar])) {
            return $result;
        }

        $calendarObject = static::getCachedCalendar($calendar);
        $calendarObject->setTimeZone($this->phpTimeZone);
        $calendarObject->setTime($this->calendar->getTime());

        $result['origin'] = \IntlDateFormatter::formatObject($calendarObject, 'yyyy-MM-dd');
        $result['extend'] = \IntlDateFormatter::formatObject($calendarObject, 'YYYY-MM-dd');
        $result['relate'] = $result['origin'];

        if (isset(static::$startYear[$calendar])) {
            $relatedGregorianYear = $calendarObject->get(\IntlCalendar::FIELD_EXTENDED_YEAR);
            $relatedGregorianYear = static::$startYear[$calendar] - 1 + $relatedGregorianYear;
            $result['relate'] = $relatedGregorianYear . \IntlDateFormatter::formatObject($calendarObject, '-MM-dd');
        }

        return $result;
    }

    /**
     * Get cached calendar
     *
     * @param string $calendar
     *
     * @return \IntlCalendar
     */
    protected static function getCachedCalendar($calendar)
    {
        if (isset(static::$cachedCalendars[$calendar])) {
            return static::$cachedCalendars[$calendar];
        }

        $locale = \Locale::getDefault() . '@calendar=' . $calendar;

        static::$cachedCalendars[$calendar] = \IntlCalendar::createInstance(null, $locale);

        return static::$cachedCalendars[$calendar];
    }
}
