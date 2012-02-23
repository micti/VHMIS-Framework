<?php

class Vhmis_Date
{
    /**
     * Thiết lập timezone chuẩn cho hệ thống
     */
    public static function setTimeZone($zone)
    {
        @date_default_timezone_set($zone);
    }

    public function __construct()
    {

    }
}