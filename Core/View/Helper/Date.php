<?php

class Vhmis_View_Helper_Date
{
    /**
     * Chuyển đổi định dạng giờ sql sang định dạng thường khác
     *
     * @param format Định dạng
     */
    public function sqlToNormal($time, $format = 'd/m/Y')
    {
        return Vhmis_Date::sqlToNormal($time, $format);
    }
}