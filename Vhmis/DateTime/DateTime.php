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

            $this->setDate($nowyear, $nowmonth, $nowday);
        } else {
            $this->modify($month . ' months');
        }

        return $this;
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
     * @return DateTime
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
     * @return DateTime
     */
    public function getYesterday()
    {
        return $this->getModifiedDate('- 1 days');
    }

    /**
     * Lấy ngày ngày mai
     *
     * @return DateTime
     */
    public function getTomorrow()
    {
        return $this->getModifiedDate('+ 1 days');
    }
}
