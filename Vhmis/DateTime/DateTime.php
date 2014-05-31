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
 */
class DateTime extends \DateTime
{

    const TIME_COMPARE_GREATER = 1;
    const TIME_COMPARE_EQUAL = 0;
    const TIME_COMPARE_LESS_THAN = -1;

    private $timeCompareGreater = self::TIME_COMPARE_GREATER;

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
    protected $startOfWeek = 'monday';

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
        //'getFirstDayOfWeek' => array('modifyThisWeek', 'first day'),
        //'getLastDayOfWeek' => array('modifyThisWeek', 'last day')
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
        $extDate = new static();
        $date = parent::createFromFormat($format, $time);
        if ($date === false) {
            return false;
        }

        $extDate->setTimestamp($date->getTimestamp());

        return $extDate;
    }

    /**
     * Set start day of week (sunday, monday ... or 0, 1, .. 6)
     *
     * @param string|int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setStartDayOfWeek($day)
    {
        if (array_search($day, $this->weekday) !== false) {
            $this->startOfWeek = $day;
            $this->weekdayOrder = $this->sortWeekday();

            return $this;
        }

        if (isset($this->weekday[$day])) {
            $this->startOfWeek = $this->weekday[$day];
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
    public function getStartDayOfWeek()
    {
        return $this->startOfWeek;
    }

    /**
     * Trả thời gian về định dạng ISO, sử dụng trong MYSQL
     *
     * @param int $type Kiểu tra về
     *                  2 Đúng nguyên định dạng ISO8601
     *                  1 Dạng yyyy-mm-dd hh:mm:ss
     *                  0 Dạng yyyy-mm-dd
     *                  3 Dạng yyyy-mm
     *
     * @return string
     */
    public function formatISO($type = 2)
    {
        if ($type == 0) {
            return $this->format('Y-m-d');
        }

        if ($type == 1) {
            return $this->format('Y-m-d H:i:s');
        }

        if ($type == 3) {
            return $this->format('Y-m');
        }

        return $this->format(DateTime::ISO8601);
    }

    /**
     * Định dang cho SQL Datetime
     *
     * @return string
     */
    public function formatSQLDateTime()
    {
        return $this->formatISO(1);
    }

    /**
     * Định dang cho SQL Date
     *
     * @return string
     */
    public function formatSQLDate()
    {
        return $this->formatISO(0);
    }

    /**
     * So sánh với một ngày bất kỳ
     *
     * @param \Vhmis\DateTime\DateTime|string $date Ngày ở dạng str hoặc DateTime
     *
     * @return int|null
     */
    public function compare($date)
    {
        if (is_string($date)) {
            $time = strtotime($date);

            if ($this->getTimestamp() > $time) {
                return $this->timeCompareGreater;
            }

            if ($this->getTimestamp() === $time) {
                return static::TIME_COMPARE_EQUAL;
            }

            return static::TIME_COMPARE_LESS_THAN;
        }

        if ($date instanceof \DateTime) {
            if ($this > $date) {
                return static::TIME_COMPARE_GREATER;
            }

            if ($this === $date) {
                return static::TIME_COMPARE_EQUAL;
            }

            return static::TIME_COMPARE_LESS_THAN;
        }

        return null;
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
        $startDayOfWeek = $date->getStartDayOfWeek();
        $date->setStartDayOfWeek($this->startOfWeek);

        // Use monday this week
        $wek1 = floor($this->modifyThisWeek('first day')->getTimestamp() / 86400 / 7);
        $wek2 = floor($date->modifyThisWeek('first day')->getTimestamp() / 86400 / 7);

        $this->setTimestamp($origin1);
        $date->setStartDayOfWeek($startDayOfWeek)->setTimestamp($origin2);

        return (int) ($wek2 - $wek1);
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
        $lastday = date('j', strtotime('last day of ' . $year . '-' . $month));

        $this->setDate($year, $month, $day);
        if ($day > $lastday) {
            $this->setDate($year, $month, $lastday);
        }

        return $this;
    }

    /**
     * Add (or sub) months
     *
     * @param int $month
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addMonthWithoutFix($month)
    {
        return $this->modify($month . ' months');
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
        $nowmonth = (int) $this->format('m');
        $nowyear = (int) $this->format('Y');
        $nowday = (int) $this->format('d');

        $year = $nowyear + $year;
        $lastday = date('j', strtotime('last day of ' . $year . '-' . $nowmonth));

        $this->setDate($year, $nowmonth, $lastday);
        if ($nowday < $lastday) {
            $this->setDate($year, $nowmonth, $nowday);
        }

        return $this;
    }

    /**
     * Add (or sub) year
     *
     * @param int $year
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function addYearWithoutFix($year)
    {
        return $this->modify($year . ' years');
    }

    /**
     * Set day of month
     *
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setDay($day)
    {
        $month = (int) $this->format('m');
        $year = (int) $this->format('Y');

        $this->setDate($year, $month, $day);

        return $this;
    }

    /**
     * Set month
     *
     * @param int $month
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setMonth($month)
    {
        $day = (int) $this->format('j');
        $year = (int) $this->format('Y');
        $lastday = (int) date('j', strtotime('last day of ' . $year . '-' . $month));

        $this->setDate($year, $month, $day);
        if ($day > $lastday) {
            $this->setDate($year, $month, $lastday);
        }

        return $this;
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
        $day = (int) $this->format('j');
        $month = (int) $this->format('m');
        $lastday = (int) date('j', strtotime('last day of ' . $year . '-' . $month));

        $this->setDate($year, $month, $day);
        if ($day > $lastday) {
            $this->setDate($year, $month, $lastday);
        }

        return $this;
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
     * Magic for set/get method
     * 
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments = null)
    {
        if(isset($this->methods[$name])) {
            $method = $this->methods[$name][0];
            $arguments = $this->methods[$name][1];
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
     * Tìm xem có quan hệ với một ngày nào đó không
     *
     * Các giá trị năm tháng tuần ngày không hơn nhau quá 1 đơn vị
     */
    public function findRelative($date)
    {
        $diffDay = $date->diffDay($this);
        $diffWeek = $date->diffWeek($this);
        $diffMonth = $date->diffMonth($this);
        $diffYear = $date->diffYear($this);

        $relative = array();

        if ($diffDay >= -1 && $diffDay <= 1) {
            $relative['d'] = $diffDay;
        }

        if ($diffWeek >= -1 && $diffWeek <= 1) {
            $relative['w'] = $diffWeek;
        }

        if ($diffMonth >= -1 && $diffMonth <= 1) {
            $relative['m'] = $diffMonth;
        }

        if ($diffYear >= -1 && $diffYear <= 1) {
            $relative['y'] = $diffYear;
        }

        return $relative;
    }

    /**
     * Lấy ngày của thời gian hiện tại (2 chữ số)
     *
     * @return string
     */
    public function getDay()
    {
        return $this->format('d');
    }

    /**
     * Lấy ngày trong tuần
     *
     * Thứ 2 -> Thứ 7 trả về 2 - 7
     * Chủ nhật -> 8 nếu chủ nhật là ngày cuối tuần
     * Chủ nhật -> 1 nếu chủ nhật là ngày đầu tuần
     *
     * @return int
     */
    public function getWeekDay()
    {
        $weekday = $this->format('N') + 1;

        if ($this->startOfWeek === 'sunday' && $weekday === 8) {
            return 1;
        }

        return $weekday;
    }

    /**
     * Lấy tháng của thời gian hiện tại (2 chữ số)
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->format('m');
    }

    /**
     * Lấy năm của thời gian hiện tại (4 chữ số)
     *
     * @return string
     */
    public function getYear()
    {
        return $this->format('Y');
    }

    /**
     * getTimestamp method return value of format('U')
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->format('U');
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
        $position = false;

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
     * Sort weekday based on start day of week
     *
     * For example: if monday is start day of week, the return will be [1,2,3,4,5,6,0]
     *
     * @return array
     */
    protected function sortWeekday()
    {
        $position = array_search($this->startOfWeek, $this->weekday);
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
