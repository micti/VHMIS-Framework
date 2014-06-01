<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime;

/**
 * Datetime class, extends from PHP Datetime class
 *
 * @method string getMonth() Get month of date (2 characters)
 * @method string getYear() Get year of date (4 characters)
 * @method string getDay() Get day of date (2 characters)
 * @method string getWeekday() Get ISO-8601 numeric representation of the day of the week
 * @method string formatISODate() Format date as ISO date format (Y-m-d)
 * @method string formatISODateTime() Format date as ISO datetime format (Y-m-d H:i:s)
 * @method string formatISOYearMonth() Format date as ISO year and month format (Y-m),
 * @method string formatSQLDate() Format date as SQL date format
 * @method string formatSQLDateTime() Format date as SQL datetime format
 * @method DateTime getFirstDayOfWeek() Get new DateTime object with date is first day of week
 * @method DateTime getLastDayOfWeek() Get new DateTime object with date is last day of week
 */
class DateTime extends \DateTime
{
    /**
     * Day of week array, index same date('w')
     * 0 to 6 : Sunday to Monday
     *
     * @var array
     */
    protected $weekday = array(
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday'
    );

    /**
     * Start day of week
     *
     * @var string
     */
    protected $startOfWeek = 1;

    /**
     * Weekday order based on start day of week
     *
     * @var array
     */
    protected $weekdayOrder = array(1, 2, 3, 4, 5, 6, 0);

    protected $methods = array(
        'setYesterday' => array('modify', 'yesterday'),
        'setTomorrow' => array('modify', 'tomorrow'),
        'setFirstDayOfMonth' => array('modify', 'first day of this month'),
        'setLastDayOfMonth' => array('modify', 'last day of this month'),
        'setFirstDayOfWeek' => array('modifyThisWeek', 'first day'),
        'setLastDayOfWeek' => array('modifyThisWeek', 'last day'),
        'getYesterday' => array('getModifiedDate', 'yesterday'),
        'getTomorrow' => array('getModifiedDate', 'tomorrow'),
        'getFirstDayOfMonth' => array('getModifiedDate', 'first day of this month'),
        'getLastDayOfMonth' => array('getModifiedDate', 'last day of this month'),
        'getFirstDayOfWeek' => array('getModifiedDateThisWeek', 'first day'),
        'getLastDayOfWeek' => array('getModifiedDateThisWeek', 'last day'),
        'getDay' => array('format', 'd'),
        'getMonth' => array('format', 'm'),
        'getYear' => array('format', 'Y'),
        'getWeekday' => array('format', 'N'),
        'formatISODate' => array('format', 'Y-m-d'),
        'formatISODateTime' => array('format', 'Y-m-d H:i:s'),
        'formatISOYearMonth' => array('format', 'Y-m'),
        'formatSQLDate' => array('formatISODate'),
        'formatSQLDateTime' => array('formatISODateTime'),
    );

    /**
     * Các class static trả về DateTime cần viết lại để trả về đúng class mới
     * sử dụng new static() để tránh luôn chuyện này xảy ra nếu tiếp tục extends
     * từ class mới
     *
     * @param string        $format
     * @param string        $time
     * @param \DateTimeZone $timezone
     *
     * @return DateTime
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        if ($timezone === null) {
            $timezone = new \DateTimeZone(date_default_timezone_get());
        }

        $extDate = new static();
        $date = parent::createFromFormat($format, $time, $timezone);

        if ($date === false) {
            return false;
        }

        $extDate->setTimestamp($date->getTimestamp());
        $extDate->setTimezone($timezone);

        return $extDate;
    }

    /**
     * Set start day of week
     * 0 - 6 (sunday, monday, ..., saturday)
     *
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setStartOfWeek($day)
    {
        $day = (int) $day;
        if (isset($this->weekday[$day])) {
            $this->startOfWeek = $day;
            $this->weekdayOrder = $this->sortWeekday();

            return $this;
        }

        return $this;
    }

    /**
     * Get start day of week
     *
     * @return string
     */
    public function getStartOfWeek()
    {
        return $this->startOfWeek;
    }

    /**
     * Find different in day between 2 dates (not effect by time)
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return int
     */
    public function diffDay($date)
    {
        $origin1 = $this->getTimestamp();
        $origin2 = $date->getTimestamp();

        $day1 = floor($this->setTime(0, 0, 0)->getTimestamp() / 86400);
        $day2 = floor($date->setTime(0, 0, 0)->getTimestamp() / 86400);

        $this->setTimestamp($origin1);
        $date->setTimestamp($origin2);

        return (int) ($day2 - $day1);
    }

    /**
     * Find different in week between 2 dates (based on week)
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return int
     */
    public function diffWeek($date)
    {
        $origin1 = $this->getTimestamp();
        $origin2 = $date->getTimestamp();
        $startOfWeek = $date->getStartOfWeek();
        $date->setStartOfWeek($this->startOfWeek);

        $this->modifyThisWeek('first day');
        $date->modifyThisWeek('first day');
        $diffDay = $this->diffDay($date);

        $this->setTimestamp($origin1);
        $date->setStartOfWeek($startOfWeek)->setTimestamp($origin2);

        return (int) ($diffDay / 7);
    }

    /**
     * Find different in month between 2 dates (based on month)
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return int
     */
    public function diffMonth($date)
    {
        $month1 = (int) $this->format('m');
        $year1 = (int) $this->format('Y');

        $month2 = (int) $date->format('m');
        $year2 = (int) $date->format('Y');

        return ($year2 * 12 + $month2) - ($year1 * 12 + $month1);
    }

    /**
     * Find different in year between 2 dates (based on year)
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return int
     */
    public function diffYear($date)
    {
        $year1 = (int) $this->format('Y');
        $year2 = (int) $date->format('Y');

        return $year2 - $year1;
    }

    /**
     * Add / sub second
     *
     * @param int $sec
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addSecond($sec)
    {
        $timestamp = $this->getTimestamp();
        $timestamp += $sec;
        $this->setTimestamp($timestamp);

        return $this;
    }

    /**
     * Add / sub minute
     *
     * @param int $min
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addMinute($min)
    {
        return $this->addSecond($min * 60);
    }

    /**
     * Thêm / giảm giờ, thay cho modify và add
     *
     * @param int $hour
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addHour($hour)
    {
        return $this->addSecond($hour * 3600);
    }

    /**
     * Add / sub day
     *
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addDay($day)
    {
        return $this->addSecond($day * 86400);
    }

    /**
     * Add / sub week
     *
     * @param int $week
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addWeek($week)
    {
        return $this->addSecond($week * 604800);
    }

    /**
     * Add / sub month and don't care about total days of month
     *
     * @param int $month
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addMonth($month)
    {
        $nowmonth = (int) $this->format('m');
        $year = (int) $this->format('Y');
        $day = (int) $this->format('j');

        $nowmonth--;
        $totalMonth = $nowmonth + $year * 12 + $month;
        $month = $totalMonth % 12 + 1;
        $year = floor($totalMonth / 12);

        $this->setNewDate($year, $month, $day);

        return $this;
    }

    /**
     * Add / sub year and don't care about total days of month
     *
     * @param int $year
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addYear($year)
    {
        $month = (int) $this->format('m');
        $nowyear = (int) $this->format('Y');
        $day = (int) $this->format('d');

        $year = $nowyear + $year;

        $this->setNewDate($year, $month, $day);

        return $this;
    }

    /**
     * Set day
     *
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setDay($day)
    {
        $month = (int) $this->format('m');
        $year = (int) $this->format('Y');
        $day = (int) $day;

        $this->setDate($year, $month, $day);

        return $this;
    }

    /**
     * Set month (1-12)
     *
     * @param int $month
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setMonth($month)
    {
        $month = (int) $month;
        if ($month < 1 || $month > 12) {
            return $this;
        }

        $year = $this->format('Y');
        $day = $this->format('d');

        return $this->setNewDate($year, $month, $day);
    }

    /**
     * Set year
     *
     * @param int $year
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setYear($year)
    {
        $year = (int) $year;
        $month = (int) $this->format('m');
        $day = (int) $this->format('d');

        return $this->setNewDate($year, $month, $day);
    }

    /**
     * Set current time
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setNow()
    {
        $this->setTimestamp(time());

        return $this;
    }

    /**
     * Magic for set/get/format method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments = null)
    {
        if (isset($this->methods[$name])) {
            $method = $this->methods[$name][0];
            $arguments = isset($this->methods[$name][1]) ? $this->methods[$name][1] : $arguments;

            return $this->$method($arguments);
        }

        return $this;
    }

    /**
     *
     * @param \Vhmis\DateTime\DateTime $date
     *
     * @return array
     */
    public function findInterval($date)
    {
        $interval = array();

        $interval['d'] = (int) $date->getDay() - (int) $this->getDay();
        $interval['M'] = (int) $date->getMonth() - (int) $this->getMonth();
        $interval['y'] = (int) $date->getYear() - (int) $this->getYear();
        $interval['h'] = (int) $date->format('g') - (int) $this->format('g');
        $interval['H'] = (int) $date->format('G') - (int) $this->format('G');
        $interval['m'] = (int) $date->format('i') - (int) $this->format('i');
        $interval['a'] = $date->format('a') !== $this->format('a') ? 1 : 0;

        return $interval;
    }

    /**
     * Find booking (dorm, hotel) interval
     *
     * @param \Vhmis\DateTime\DateTime
     *
     * @return \DateTimeInterval
     */
    public function findBookingInterval($date)
    {
        $diff = $this->diff($date);

        $diff->m += $diff->y * 12;
        $diff->y = 0;

        if ($this->getDay() === $date->getDay()) {
            if ($diff->d >= 27) {
                $diff->d = 0;
                $diff->m++;

                return $diff;
            }

            if ($diff->d <= 4 & $diff->d > 0) {
                $diff->d = 0;

                return $diff;
            }
        }

        if ($diff->d >= 30) {
            $diff->d = 0;
            $diff->m++;
        }

        return $diff;
    }

    /**
     * Find relative
     *
     * @param DateTime $date
     *
     * @return array
     */
    public function findRelative($date)
    {
        $diff = array();

        $diff['d'] = $date->diffDay($this);
        $diff['w'] = $date->diffWeek($this);
        $diff['m'] = $date->diffMonth($this);
        $diff['y'] = $date->diffYear($this);

        $relative = array();

        foreach ($diff as $field => $value) {
            if (abs($value) <=1) {
                $relative[$field] = $value;
            }
        }

        return $relative;
    }

    /**
     * Same as modify() method but return new DateTime object
     *
     * @param string $modify
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function getModifiedDate($modify)
    {
        $new = clone $this;

        $new->modify($modify);

        return $new;
    }

    /**
     * Modify date in this week
     *
     * Modify string can be 'first day', 'last day' or name of weekday
     *
     * @param string $modify
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function modifyThisWeek($modify)
    {
        if ($modify === 'first day') {
            $position = 0;
        } elseif ($modify === 'last day') {
            $position = 6;
        } else {
            $weekday = array_search($modify, $this->weekday);
            if ($weekday === false) {
                return $this;
            }

            $position = array_search($weekday, $this->weekdayOrder);
        }

        $currentPosition = array_search($this->format('w'), $this->weekdayOrder);

        return $this->addDay($position - $currentPosition);
    }

    /**
     * Same as modifyThisWeek() method but return new DateTime object
     *
     * @param string $modify
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function getModifiedDateThisWeek($modify)
    {
        $new = clone $this;

        $new->modifyThisWeek($modify);

        return $new;
    }

    /**
     * Set date
     *
     * Instead of adjust day, month, year if day is out range
     * this method will use first and last day of month if it happens
     *
     * @param int $year
     * @param int $month
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    protected function setNewDate($year, $month, $day)
    {
        $year = (int) $year;
        $month = (int) $month;
        $day = (int) $day;

        $lastday = (int) date('d', strtotime('last day of ' . $year . '-' . $month));

        $this->setDate($year, $month, $day);

        if ($day > $lastday) {
            $this->setDate($year, $month, $lastday);
        }

        return $this;
    }

    /**
     * Sort weekday based on start day of week
     *
     * For example: if monday is start day of week, the return will be [1,2,3,4,5,6,0]
     *
     * @return array
     */
    protected function sortWeekday()
    {
        $position = $this->startOfWeek;
        $weekdayOrder = array();

        for ($i = 0; $i < 7; $i++) {
            if ($i >= $position) {
                $weekdayOrder[$i - $position] = $i;
            } else {
                $weekdayOrder[7 - $position + $i] = $i;
            }
        }

        return $weekdayOrder;
    }
}
