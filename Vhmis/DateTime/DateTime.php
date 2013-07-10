<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_DateTime
 * @since Vhmis v2.0
 */

namespace Vhmis\DateTime;

/**
 * Class để xử lý ngày giờ, được mở rộng từ class DateTime của PHP
 *
 * @category Vhmis
 * @package Vhmis_DateTime
 * @subpackage DateTime
 */
class DateTime extends \DateTime
{
    const TIME_COMPARE_GREATER = 1;
    const TIME_COMPARE_EQUAL = 0;
    const TIME_COMPARE_LESS_THAN = -1;

    /**
     * Ngày bắt đầu trong tuần
     * @var string
     */
    protected $startOfWeek = 'monday';

    /**
     * Các class static trả về DateTime cần viết lại để trả về đúng class mới
     * sử dụng new static() để tránh luôn chuyện này xảy ra nếu tiếp tục extends
     * từ class mới
     *
     * @param type $format
     * @param type $time
     * @return DateTime
     */
    static public function createFromFormat($format, $time)
    {
        $ext_dt = new static();
        $dt = parent::createFromFormat($format, $time);
        if ($dt === false)
            return false;
        $ext_dt->setTimestamp($dt->getTimestamp());
        return $ext_dt;
    }

    /**
     * Thiết lập ngày đầu tuần, monday hoặc sunday
     *
     * @param string $day
     * @return \Vhmis\DateTime\DateTime
     */
    public function setStartDayOfWeek($day)
    {
        if ($day === 'sunday') {
            $this->startOfWeek = 'sunday';
        } else {
            $this->startOfWeek = 'monday';
        }

        return $this;
    }

    /**
     * Trả thời gian về định dạng ISO, sử dụng trong MYSQL
     *
     * @param int $type Kiểu tra về 2 Đúng nguyên định dạng ISO8601 1 Dạng yyyy-mm-dd
     *        hh:mm:ss 0 Dạng yyyy-mm-dd
     * @return string
     */
    public function formatISO($type = 2)
    {
        if ($type == 0) {
            return $this->format('Y-m-d');
        } elseif ($type == 1) {
            return $this->format('Y-m-d H:i:s');
        } else {
            return $this->format(DateTime::ISO8601);
        }
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
     * @return int|null
     */
    public function compare($date)
    {
        if (is_string($date)) {
            $time = strtotime($date);
            if ($this->getTimestamp() > $time) {
                return static::TIME_COMPARE_GREATER;
            } elseif ($this->getTimestamp() === $time) {
                return static::TIME_COMPARE_EQUAL;
            } else {
                return static::TIME_COMPARE_LESS_THAN;
            }
        }

        if ($date instanceof \DateTime) {
            if ($this > $date) {
                return static::TIME_COMPARE_GREATER;
            } elseif ($this === $date) {
                return static::TIME_COMPARE_EQUAL;
            } else {
                return static::TIME_COMPARE_LESS_THAN;
            }
        }

        return null;
    }

    /**
     * Tính số ngày khác nhau (không quan tâm đến đến thời gian)
     * Giá trị âm nghĩa là ngày được so sánh bé hơn
     *
     * Ví dụ 2013-12-30 00:00:00 với 2013-12-31 11:59:59 khác nhau 1 ngày
     *
     * @param \Vhmis\DateTime\DateTime $date
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

        return $day2 - $day1;
    }

    /**
     * Tính số tuần khác nhau (không quan tâm đến đến ngày nào trong tuần)
     * Giá trị âm nghĩa là ngày được so sánh bé hơn
     *
     * Ví dụ Chủ Nhật 2013-06-29 23:59:59 với Thứ 2 2013-06-30 08:12:12 khác nhau 1 tuần nếu thứ 2 là ngày đầu tuần
     * và khác nhau 0 tuần nếu chủ nhật là ngày đầu tuần
     *
     * @param \Vhmis\DateTime\DateTime $date
     * @return int
     */
    public function diffWeek($date)
    {
        $origin1 = $this->getTimestamp();
        $origin2 = $date->getTimestamp();

        // Use monday this week
        $wek1 = floor($this->modify('monday this week')->getTimestamp() / 86400 / 7);
        $wek2 = floor($date->modify('monday this week')->getTimestamp() / 86400 / 7);

        $this->setTimestamp($origin1);
        $date->setTimestamp($origin2);

        return $wek2 - $wek1;
    }

    /**
     * Tính số tháng khác nhau (không quan tâm đến đến ngày giờ ... nào trong tháng)
     *
     * Ví dụ 2016-04-30 08:12:12 với 2013-06-01 23:59:59 khác nhau -34 tháng
     *
     * @param \Vhmis\DateTime\DateTime $date
     * @return int
     */
    public function diffMonth($date)
    {
        $month1 = (int) $this->format('m');
        $year1 = (int) $this->format('Y');

        $month2 = (int) $date->format('m');
        $year2 = (int) $date->format('Y');

        if ($year1 === $year2) {
            return $month2 - $month1;
        }

        if ($year1 < $year2) {
            return ($year2 - $year1 - 1) * 12 + $month2 + (12 - $month1);
        }

        return (($year1 - $year2 - 1) * 12 + $month1 + (12 - $month2)) * -1;
    }

    /**
     * Tính số năm khác nhau, không quan tâm đến tháng ngày giờ ...
     *
     * @param \Vhmis\DateTime\DateTime $date
     * @return int
     */
    public function diffYear($date)
    {
        $year1 = (int) $this->format('Y');
        $year2 = (int) $date->format('Y');

        return $year2 - $year1;
    }

    /**
     * Thêm / giảm ngày, thay cho modify và add
     *
     * @param int $day
     * @return \Vhmis\DateTime\DateTime
     */
    public function addDay($day)
    {
        $a = $this->getTimestamp();
        $a += $day * 86400;
        $this->setTimestamp($a);

        return $this;
    }

    /**
     * Thêm / giảm tuần, thay cho modify và add
     *
     * @param int $week
     * @return \Vhmis\DateTime\DateTime
     */
    public function addWeek($week)
    {
        $a = $this->getTimestamp();
        $a += $week * 86400 * 7;
        $this->setTimestamp($a);

        return $this;
    }

    /**
     * Hàm thêm / giảm số tháng vào ngày hiện tại
     *
     * Ở đây có 2 trường hợp:
     * - Thêm tháng dựa theo số ngày trong tháng, cách thêm này tương tự như hàm
     * modify,add,sub
     * khi đó tham số thứ 2 nhận giá trị false
     * - Thêm tháng chỉ dựa vào tháng hiện tại, khi đó tham số thứ 2 nhận giá
     * trị true
     *
     * @param int $month Số lượng tháng cần thêm vào (sử dụng số âm nếu muốn giảm đi)
     * @param bool $fix Sử dụng giá trị true nếu chỉ muốn dựa vào tháng để tính toán
     * @return \Vhmis\DateTime\DateTime
     */
    public function addMonth($month, $fix = true)
    {
        // Chỉ cộng thêm tháng, không dựa theo ngày trong tháng đó
        if ($fix === true) {
            $nowmonth = (int) $this->format('m');
            $nowyear = (int) $this->format('Y');
            $nowday = (int) $this->format('d');

            // Sử dụng 0-11 để biểu diễn tháng
            $nowmonth--;

            // Tính toán tháng mới, năm mới
            $totalmonth = $nowmonth + $nowyear * 12 + $month;
            $nowmonth = $totalmonth % 12 + 1; // + 1 để trả lại tháng 1-12
            $nowyear = $totalmonth / 12; // Số nguyên
            $lastday = date('j', strtotime('last day of ' . $nowyear . '-' . $nowmonth));
            if ($nowday < $lastday) {
                $this->setDate($nowyear, $nowmonth, $nowday);
            } else {
                $this->setDate($nowyear, $nowmonth, $lastday);
            }
        } else {
            $this->modify($month . ' months');
        }

        return $this;
    }

    public function addYear($year, $fix = true)
    {
        $nowmonth = (int) $this->format('m');
        $nowyear = (int) $this->format('Y');
        $nowday = (int) $this->format('d');

        $year = $nowyear + $year;

        if ($fix === true) {

            $lastday = date('j', strtotime('last day of ' . $year . '-' . $nowmonth));
            if ($nowday < $lastday) {
                $this->setDate($year, $nowmonth, $nowday);
            } else {
                $this->setDate($year, $nowmonth, $lastday);
            }
        } else {
            $this->setDate($year, $nowmonth, $nowday);
        }

        return $this;
    }

    /**
     * Thiết lập ngày
     *
     * @param int $day
     * @return \Vhmis\DateTime\DateTime
     */
    public function setDay($day)
    {
        $nowmonth = (int) $this->format('m');
        $nowyear = (int) $this->format('Y');

        $this->setDate($nowyear, $nowmonth, $day);

        return $this;
    }

    /**
     * Thiết lập tháng
     *
     * @param int $month
     * @return \Vhmis\DateTime\DateTime
     */
    public function setMonth($month)
    {
        $nowday = (int) $this->format('j');
        $nowyear = (int) $this->format('Y');
        $lastday = date('j', strtotime('last date of ' . $nowyear . '-' . $month));

        if ($nowday <= $lastday) {
            $this->setDate($nowyear, $month, $nowday);
        } else {
            $this->setDate($nowyear, $month, $lastday);
        }

        return $this;
    }

    /**
     * Thiết lập lại ngày giờ hiện tại
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function setNow()
    {
        $this->setTimestamp(time());

        return $this;
    }

    /**
     *
     * @param \Vhmis\DateTime\DateTime $date
     * @return type
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

        if($diffDay >= -1 && $diffDay <= 1) {
            $relative['d'] = $diffDay;
        }

        if($diffWeek >= -1 && $diffWeek <= 1) {
            $relative['w'] = $diffWeek;
        }

        if($diffMonth >= -1 && $diffMonth <= 1) {
            $relative['m'] = $diffMonth;
        }

        if($diffYear >= -1 && $diffYear <= 1) {
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
     * Viết lại phương thức getTimestamp
     * Trong một số trường hợp phương thức getTimestamp trả về false thay vì số
     * âm
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->format('U');
    }

    /**
     * Tương tự như phương thức modify nhưng trả về đối tượng DateTime mới
     *
     * @param string $modify
     * @return \Vhmis\DateTime\DateTime
     */
    public function getModifiedDate($modify)
    {
        $new = clone $this;

        $new->modify($modify);

        return $new;
    }

    /**
     * Lấy ngày hôm qua
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function getYesterday()
    {
        return $this->getModifiedDate('- 1 days');
    }

    /**
     * Lấy ngày ngày mai
     *
     * @return \Vhmis\DateTime\DateTime
     */
    public function getTomorrow()
    {
        return $this->getModifiedDate('+ 1 days');
    }

    /**
     * Viết lại phương thức modify
     *
     * Mặc định PHP thiết lập ngày chủ nhật là ngày đầu tuần do đó một số chuỗi để hiệu chỉnh thời gian liên quan đến
     * tuần có thể trả về ngày không chính xác nếu ta xem ngày thứ hai là ngày đầu tuần.
     *
     * Phương thức này được viết lại để xét các trường hợp này, còn lại sử dụng mặc định
     *
     * @param mixed $modify
     * @return \Vhmis\DateTime\DateTime
     */
    public function modify($modify)
    {
        // Sunday, php default start day of week is sunday and if your calendar start day of week is monday
        if ($this->format('N') == 7 && $this->startOfWeek === 'monday') {
            $matches = array();
            $pattern = '/this week|next week|previous week|last week/i';
            if (preg_match($pattern, $modify, $matches)) {
                $modify = str_replace($matches[0], '-7 days ' . $matches[0], $modify);
            }
        }

        return parent::modify($modify);
    }
}
