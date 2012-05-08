<?php

class Vhmis_View_Helper_Date
{
    private $_vietnameseWeekday = array('Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7');

    /**
     * Chuyển đổi định dạng giờ sql sang định dạng thường khác
     *
     * @param format Định dạng
     */
    public function sqlToNormal($time, $format = 'd/m/Y')
    {
        return Vhmis_Date::sqlToNormal($time, $format);
    }

    /**
     * Chuyển đổi định dạng giờ sql sang định dạng đầy đủ chữ VN
     *
     * @param time Giờ dạng sql
     */
    public function sqlToFullVietnameseText($time)
    {
        $text1 = $this->sqlToNormal($time, 'w');
        $text2 = $this->sqlToNormal($time, 'd/m/Y h:i:s');

        return $this->_vietnameseWeekday[$text1] . ' ngày ' . $text2;
    }
}