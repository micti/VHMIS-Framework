<?php

/**
 * Class xử lý ngày tháng
 *
 * Chú ý : hiện tại chỉ hổ trợ năm 1900 đến năm 2038 (unix timestamp 32bit)
 */

class Vhmis_Date
{
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
     *
     */
    public function getUnixTime()
    {
        return $this->_time;
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
}