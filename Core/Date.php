<?php

/**
 * Class xử lý ngày tháng
 *
 * Chú ý : hiện tại chỉ hổ trợ năm 1900 đến năm 2038 (unix timestamp 32bit)
 */

class Vhmis_Date
{
    // Hằng sô
    const DAYTOSECOND = 86400;
    const HOURTOSECOND = 3600;
    const MINUTETOSECOND = 60;
    protected $_weekdayToEnglish = array(
        '1' => 'sunday',
        '2' => 'monday',
        '3' => 'tuesday',
        '4' => 'wednesday',
        '5' => 'thursday',
        '6' => 'friday',
        '7' => 'saturday',
        '8' => 'sunday'
    );
    protected $_weekdayPositionToEnglish = array(
        '0' => 'all',
        '1' => 'first',
        '2' => 'second',
        '3' => 'third',
        '4' => 'fourth',
        '5' => 'last'
    );

    /**
     * Thiết lập timezone chuẩn cho hệ thống
     */
    public static function setTimeZone($zone)
    {
        @date_default_timezone_set($zone);
    }

    /**
     * Chênh lệch thời gian của hệ thống với GMT
     */
    protected $_offsetServer;

    /**
     * Giờ đang được thiết lập của đối tượng (ứng với timezone của hệ thống) dạng unix timestamp
     */
    protected $_time;

    /**
     * Chênh lệch thời gian của giờ đang được thiệt lập với GMT
     */
    protected $_offset;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        $this->_offsetServer = date("Z");
        $this->time();
    }

    /**
     * Thiết lập thời gian
     *
     * Nếu param là mảng thì param có dạng
     * 'd'
     * 'm'
     * 'y'
     * 'h'
     * 'm'
     * 's'
     *
     * @param mixed $param Thông tin ngày giờ khởi tạo (dạng array,string),  còn nếu null (mặc định) thì trả về ngày giờ hiện tại
     * @param mixed $offset Chênh lệch theo giờ với UTC, Nếu là null thì offset = offset của server
     */
    public function time($param = null, $offset = null)
    {
        if(is_array($param))
        {
            foreach($param as $i => $j)
            {
                $$i = intval($j);
            }
            $time = mktime($h, $m, $s, $m, $d, $y);
        }
        else if(is_numeric($param))
        {
            $time = floor($param);
        }
        else if(is_string($param))
        {
            $time = strtotime($param);
        }
        else if($param === null)
        {
            $time = time();
        }

        if($time === false) return false;

        $this->_time = $time;

        if($offset != null && is_numeric($offset))
        {
            $this->_offset = $offset * 3600 - $this->_offsetServer;
            $this->_time -= $this->_offset;
        }
        else
        {
            $this->_offset = $this->_offsetServer;
        }

        return true;
    }

    /**
     * Thiếp lập tháng
     */
    public function setMonth($month)
    {
        $string = date('Y-' . $month . '-d H:i:s', $this->_time);
        $this->time($string);
    }

    /**
     * Lấy ngày
     */
    public function getDay()
    {
        return date('d', $this->_time);
    }

    /**
     *
     */
    public function getUnixTime()
    {
        return $this->_time;
    }

    public function getUnixTimeGMT()
    {
        return $this->_time + $this->_offsetServer;
    }

    public function getWeekday()
    {
        return date('w', $this->_time) + 1; // đảm bảo chủ nhật là 1, thứ 2 -> thứ 7 ứng với 2 -> 7
    }

    /**
     * Thêm ngày vào thời gian hiện tại
     */
    function addDay($day)
    {
        $this->_time += $day * Vhmis_Date::DAYTOSECOND;
        return $this;
    }

    /**
     * Thêm tuần vào thời gian hiện tại
     */
    function addWeek($week)
    {
        $this->_time += $week * 7 * Vhmis_Date::DAYTOSECOND;
        return $this;
    }

    /**
     * Thêm tháng vào thời gian hiện tại
     */
    function addMonth($month)
    {
        $this->_time = strtotime('+' . $month. ' month', $this->_time);
        return $this;
    }

    /**
     * Thêm năm vào thời gian hiện tại
     */
    function addYear($year)
    {
        $this->_time = strtotime('+' . $year. ' year', $this->_time);
        return $this;
    }

    /**
     * Ngày cuối tuần
     */
    public function endDateOfWeek($return = '')
    {
        $weekday = date('N', $this->_time);
        $add = 7 - $weekday; // Số ngày cách nhau so với ngày cuối tuần
        $time = $this->_time + $add * self::DAYTOSECOND;

        if($return == 'Vhmis_Date')
        {
            $newDate = new Vhmis_Date();
            $newDate->time($time);
            return $newDate;
        }
        if($return == 'UnixGMT')
        {
            return $time + $this->_offsetServer;
        }
        else
        {
            return $time;
        }
    }

    /**
     * Ngày cuối tuần
     */
    public function startDateOfWeek($return = '')
    {
        $weekday = date('N', $this->_time);
        $add = 1 - $weekday; // Số ngày cách nhau so với ngày đầu tuần (số âm)
        $time = $this->_time + $add * self::DAYTOSECOND;

        if($return == 'Vhmis_Date')
        {
            $newDate = new Vhmis_Date();
            $newDate->time($time);
            return $newDate;
        }
        if($return == 'UnixGMT')
        {
            return $time + $this->_offsetServer;
        }
        else
        {
            return $time;
        }
    }

    /**
     * Ngày đầu tiên của tháng
     */
    public function startDateOfMonth($return = '')
    {
        $string = date('Y-m-01 H:i:s', $this->_time);
        $time = strtotime($string);

        if($return == 'Vhmis_Date')
        {
            $newDate = new Vhmis_Date();
            $newDate->time($time);
            return $newDate;
        }
        if($return == 'UnixGMT')
        {
            return $time + $this->_offsetServer;
        }
        else
        {
            return $time;
        }
    }

    /**
     * Ngày đầu tiên của năm
     */
    public function startDateOfYear($return = '')
    {
        $string = date('Y-01-01 H:i:s', $this->_time);
        $time = strtotime($string);

        if($return == 'Vhmis_Date')
        {
            $newDate = new Vhmis_Date();
            $newDate->time($time);
            return $newDate;
        }
        if($return == 'UnixGMT')
        {
            return $time + $this->_offsetServer;
        }
        else
        {
            return $time;
        }
    }

    /**
     * Tính khoảng thời gian theo ngày giữa ngày hiện tại với một một ngày khác
     * trả về dương nếu ngày bị đem ra so lớn hơn ngày so, âm nếu ngược lại
     *
     * @param Vhmis_Date $date Ngày bị đem ra so
     * @return Khoảng cách thời gian theo ngày
     */
    public function differentDay($date)
    {
        $day1 = floor($this->getUnixTimeGMT() / self::DAYTOSECOND);
        $day2 = floor($date->getUnixTimeGMT() / self::DAYTOSECOND);

        return $day2 - $day1;
    }

    /**
     * Tính khoảng thời gian theo tuần giữa ngày hiện tại với một ngày khác
     * Chú ý khoảng cách tuần tính theo độ chênh lệch thứ tự tuần, không phải là lấy chệnh lệch ngày chia cho 7
     * Ví dụ:
     * - khoảng cách giữa thứ 2 tuần trước với thứ 7 tuần này là 1 tuần
     * - khoảng cách giữa thứ 7 tuần trước với thứ 2 tuần này là 1 tuần
     *
     * @param Vhmis_Date $date Ngày bị đem ra so
     * @return Khoảng cách thời gian theo tuần
     */
    public function differentWeek($date)
    {
        // Cùng lấy mốc thời gian đầu tuần
        $day1 = floor($this->startDateOfWeek('UnixGMT') / self::DAYTOSECOND);
        $day2 = floor($date->startDateOfWeek('UnixGMT') / self::DAYTOSECOND);

        return ($day2 - $day1) / 7;
    }

    /**
     * Tính khoảng thời gian theo tháng giữa ngày hiện tại với một ngày khác
     *
     * @param Vhmis_Date $date Ngày bị đem ra so
     * @return Khoảng cách thời gian theo tháng
     */
    public function differentMonth($date)
    {
        // Cùng lấy mốc thời gian đầu tuần
        $month1 = date('n', $this->_time);
        $year1 = date('Y', $this->_time);
        $month2 = date('n', $date->getUnixTime());
        $year2 = date('Y', $date->getUnixTime());

        if($year1 == $year2) return $month2 - $month1;
        elseif($year2 > $year1) return $month2 + 12 - $month1 + 12 * ($year2 - $year1 - 1);
        else return -$month1 - 12 + $month2 - 12 * ($year1 - $year2 - 1);
    }

    /**
     * Tính khoảng thời gian theo năm giữa ngày hiện tại với một ngày khác
     *
     * @param Vhmis_Date $date Ngày bị đem ra so
     * @return Khoảng cách thời gian theo năm
     */
    public function differentYear($date)
    {
        // Cùng lấy mốc thời gian đầu tuần
        $year1 = date('Y', $this->_time);
        $year2 = date('Y', $date->getUnixTime());

        return $year2 - $year1;
    }

    /**
     * Tính số ngày của tháng hiện tại
     */
    public function daysInMonth()
    {
        return date('t', $this->_time);
    }

    /**
     * Tìm các ngày thứ (hai, ba, bốn, năm, sáu, bảy, chủ nhật) trong tháng
     */
    public function daysOfWeekdayInMonth($weekday, $position)
    {
        if($position == 0) // tìm hết
        {
            $days = array();
            $day = date('j', strtotime( $this->_weekdayPositionToEnglish['1'] . ' ' . $this->_weekdayToEnglish[$weekday] . ' of this month', $this->_time));

            $days[] = $day;

            $total = $this->daysInMonth();
            $day += 7;
            while($day < $total)
            {
                $days[] = $day;
                $day += 7;
            }

            return $days;
        }
        else // tìm 1 vị trí
        {
            return $day = date('j', strtotime($this->_weekdayPositionToEnglish[$position] . ' ' . $this->_weekdayToEnglish[$weekday] . ' of this month', $this->_time));
        }
    }

    /**
     * Xuất ra thời gian đã qua của thời gian ở đối tượng so với thời gian hiện tại
     * 3 phút trước, 2 giờ 3 phút trước ....
     * Kết quả trả về mạng tính tương đối (vì một năm = 365 ngày, 1 = 30 ngày)
     *
     * @param int $deep Số lượng tối đa đại lương thời gian cần thông báo
     */
    public function toAgo($deep = 2)
    {
        $now = time();
        if($now < $this->_time)
        {
            //function $this->toWait();
            return;
        }

        $pass = $now - $this->_time;

        if($pass == 0) return 'vừa mới';

        // Tính số các đại lượng năm, tháng, tuần, ngày, giờ, phút, giây
        $year = floor($pass / 31536000);
        $pass -= $year * 31536000;
        $month = floor($pass / 2592000);
        $pass -= $month * 2592000;
        $week = floor($pass / 604800);
        $pass -= $week * 604800;
        $day = floor($pass / 86400);
        $pass -= $day * 86400;
        $hour = floor($pass / 3600);
        $pass -= $hour * 3600;
        $min = floor($pass / 60);
        $pass -= $min * 60;
        $sec = $pass;

        // Chuỗi kết quả
        $string = '';

        // Lấy chuỗi
        if($deep != 0 && $year != 0) {
            $string .= $year . ' năm ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $month != 0) {
            $string .= $month . ' tháng ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $week != 0) {
            $string .= $week . ' tuần ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $day != 0) {
            $string .= $day . ' ngày ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $hour != 0) {
            $string .= $hour . ' giờ ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $min != 0) {
            $string .= $min . ' phút ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        if($deep != 0 && $sec != 0) {
            $string .= $sec . ' giây ';
            $deep--;
            if($deep == 0) return $string . 'trước';
        }

        return $string . 'trước';
    }

    /**
     * Xuất ra dạng dd-mm-yyyy
     */
    public function __toString()
    {
        return date('d-m-Y', $this->_time);
    }

    /**
     * Xuất ra để lưu cơ sở dữ liệu
     */
    public function toSQL($full = true)
    {
        return $full ? date('Y-m-d H:i:s', $this->_time) : date('Y-m-d', $this->_time);
    }

    /**
     * Xuất ra dành cho RSS
     */
    public function toRSS()
    {
        //
    }

    /**
     * Chuyển đổi nhanh ngày tháng theo SQL sang ngày tháng bình thường
     *
     * @param
     */
    public static function fromSQLtoNormal($time, $full)
    {
        return $full ? date('d-m-Y H:i:s', strtotime($time)) : date('d-m-Y', strtotime($time));
    }

    public static function fromTimeToSQL($time, $full)
    {
        return $full ? date('Y-m-d H:i:s', $time) : date('Y-m-d', $time);
    }

    /**
     * Tính số ngày trong tháng, năm
     */
    public static function getDaysInMonth($month, $year)
    {
        // Xem lại công thức này
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
}