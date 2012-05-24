<?php

/**
 * Dùng để tính thời gian lặp lại
 */

class Vhmis_Date_Repeat
{
    protected $_baseDate;

    protected $_nowDate;

    protected $_endDate;

    /**
     * Thiết lập
     */
    public function __construct($base, $now, $end)
    {
        $this->_baseDate = new Vhmis_Date();
        $this->setBaseDate($base);

        $this->_nowDate = new Vhmis_Date();
        $this->setNowDate($now);

        $this->_endDate = new Vhmis_Date();
        $this->setEndDate($end);

        $this->_runDate = new Vhmis_Date();
    }

    /**
     * Thiết lập ngày gốc
     */
    public function setBaseDate($date)
    {
        $this->_baseDate->time($date);
    }

    /**
     * Thiết lập ngày hiện tại
     */
    public function setNowDate($date)
    {
        $this->_nowDate->time($date);
    }

    /**
     * Thiết lập ngày kết thúc
     */
    public function setEndDate($date)
    {
        $this->_endDate->time($date);
    }

    /**
     * Tính thời gian lặp lại theo tần suất ngày
     * Rule là mảng như sau
     * 'freq' => Tần suất (ngày) cho 1 lần thực hiện
     *
     * @param array $rule Luật lặp lại
     * @return array Các ngày lặp lại
     */
    public function calculateDailyRepeat($rule)
    {
        // Mảng chứa ngày trả về
        $repeatDates = array();

        // Kiểm tra
        if(!isset($rule['freq']) || !is_numeric($rule['freq'])) return $repeatDates;

        // Tính ngày lặp tiếp theo
        if($this->_baseDate->getUnixTime() < $this->_nowDate->getUnixTime()) // Nếu ngày hiện tại muộn hơn ngày gốc
        {
            // Khoảng cách
            $days = abs($this->_nowDate->differentDay($this->_baseDate));

            // Tính khoảng cách giữa ngày hiện tại với ngày xảy ra sự kiện kế tiếp
            $offset = $days >= $rule['freq'] ? $rule['freq'] - ($days % $rule['freq']) : $rule['freq'] - $days;
            if($offset == $rule['freq']) $offset = 0;

            // Thiết lập
            $this->_runDate->time($this->_nowDate->toSQL(false));
            $this->_runDate->addDay($offset);
        }
        else // Nếu ngày hiện tại sớm hơn ngày gốc
        {
            $this->_runDate->time($this->_baseDate->toSQL(false));
        }

        // Thực hiện tìm các ngày lặp cho đến khi chạm mốc thời gian dừng
        while($this->_runDate->differentDay($this->_endDate) >= 0)
        {
            $repeatDates[] = $this->_runDate->toSQL(false); // dạng YYYY-mm-dd
            $this->_runDate->addDay($rule['freq']);
        }

        return $repeatDates;
    }

    /**
     * Tính thời gian lặp lại theo tần suất tuần
     * Rule là mảng như sau
     * 'freq' => Tần suất (tuần) cho 1 đợt thực hiện
     * 'weekdays' => Các thứ ngày xáy ra sự kiện trong tuần (ngăn cách bằng dấu ,  sử dụng 1-7, 1 là chủ nhật, 2->7 thứ 2 đến thứ 7)
     *
     * @param array $rule Luật lặp lại
     * @return array Các ngày lặp lại
     */
    public function calculateWeeklyRepeat($rule)
    {
        // Mảng chứa ngày trả về
        $repeatDates = array();

        // Kiểm tra
        if(!isset($rule['freq']) || !is_numeric($rule['freq'])) return $repeatDates;

        // Tính tuần lặp tiếp theo
        if($this->_baseDate->getUnixTime() < $this->_nowDate->getUnixTime()) // Nếu ngày hiện tại muộn hơn ngày gốc
        {
            // Khoảng cách
            $week = abs($this->_nowDate->differentWeek($this->_baseDate));

            // Tính khoảng cách giữa ngày hiện tại với ngày xảy ra sự kiện kế tiếp
            $offset = $week >= $rule['freq'] ? $rule['freq'] - ($week % $rule['freq']) : $rule['freq'] - $week;
            if($offset == $rule['freq']) $offset = 0;

            // Thiết lập
            $this->_runDate->time($this->_nowDate->toSQL(false));
            $this->_runDate->addWeek($offset);
        }
        else // Nếu ngày hiện tại sớm hơn ngày gốc
        {
            $this->_runDate->time($this->_baseDate->toSQL(false));
        }

        // Trả về ngày đầu tuần
        $this->_runDate = $this->_runDate->startDateOfWeek('Vhmis_Date'); // Thứ 2

        // Thực hiện tìm các ngày lặp cho đến khi chạm mốc thời gian dừng
        while($this->_runDate->differentDay($this->_endDate) >= 0)
        {
            // Duyệt các ngày xảy ra sự kiện trong trong tuần
            foreach($rule['weekday'] as $weekday)
            {
                // Tính ngày lệch của ngày xảy ra sự kiện với ngày đầu tuần
                $days = $weekday - 2;
                if($days == -1) $days = 6;

                // Bỏ qua nếu có sai sót
                if($days > 6) continue;

                $time = $this->_runDate->getUnixTime() + $days * Vhmis_Date::DAYTOSECOND;

                // Vẫn có trường hợp thời gian xảy ra trước thời gian hiện tại
                if($time < $this->_nowDate->getUnixTime() || $time > $this->_endDate->getUnixTime()) continue;

                $repeatDates[] = Vhmis_Date::fromTimeToSQL($time, false);
            }

            // Tuần kế tiếp
            $this->_runDate->addWeek($rule['freq']);
        }

        return $repeatDates;
    }

    /**
     * Tính thời gian lặp lại theo tần suất tháng
     * Rule là mảng như sau
     * 'freq' => Tần suất (tháng) cho 1 đợt thực hiện
     * 'type' => Loại lặp lại (lặp theo ngày trong tháng, hoặc lặp theo ngày trong tuần)
     * 'weekday' => Thứ ngày xáy ra sự kiện trong tuần của sự kiện
     * 'weekday_position' => Vị trí tuần xảy ra sự kiện (0 là mỗi tuần, 5 cuối cùng của tháng, 1-4 là vị trí)
     * 'monthday' => Các ngày xảy ra sự kiện trong tháng
     *
     * @param array $rule Luật lặp lại
     * @return array Các ngày lặp lại
     */
    public function calculateMonthlyRepeat($rule)
    {
        // Mảng chứa ngày trả về
        $repeatDates = array();

        // Kiểm tra
        if(!isset($rule['freq']) || !is_numeric($rule['freq'])) return $repeatDates;

        // Tính tháng lặp tiếp theo
        if($this->_baseDate->getUnixTime() < $this->_nowDate->getUnixTime()) // Nếu ngày hiện tại muộn hơn ngày gốc
        {
            // Khoảng cách
            $month = abs($this->_nowDate->differentMonth($this->_baseDate));

            // Tính khoảng cách giữa ngày hiện tại với ngày xảy ra sự kiện kế tiếp
            $offset = $month >= $rule['freq'] ? $rule['freq'] - ($month % $rule['freq']) : $rule['freq'] - $month;
            if($offset == $rule['freq']) $offset = 0;

            // Thiết lập
            $this->_runDate->time($this->_nowDate->toSQL(false));
            $this->_runDate->addMonth($offset);
        }
        else // Nếu ngày hiện tại sớm hơn ngày gốc
        {
            $this->_runDate->time($this->_baseDate->toSQL(false));
        }

        // Trả về ngày tiên của tháng
        $this->_runDate = $this->_runDate->startDateOfMonth('Vhmis_Date'); // Thứ 2

        // Thực hiện tìm các ngày lặp cho đến khi chạm mốc thời gian dừng
        while($this->_runDate->differentDay($this->_endDate) >= 0)
        {
            // Nếu lặp theo ngày trong tuần, trước hết ta tìm các ngày trong tháng tương ứng của nó
            // và lưu vào giá trị của $rule['monthday']
            if($rule['type'] == 1)
            {
                $days = $this->_runDate->daysOfWeekdayInMonth($rule['weekday'], $rule['weekday_position']);
                if(is_array($days)) $rule['monthday'] = $days;
                else $rule['monthday'] = array($days);
            }

            // Duyệt các ngày xảy ra sự kiện trong trang
            foreach($rule['monthday'] as $day)
            {
                // Tính ngày lệch của ngày xảy ra sự kiện với ngày đầu tháng
                $days = $day - 1;

                $time = $this->_runDate->getUnixTime() + $days * Vhmis_Date::DAYTOSECOND;

                // Vẫn có trường hợp thời gian xảy ra trước thời gian hiện tại và sau thời gian dừng
                if($time < $this->_nowDate->getUnixTime() || $time > $this->_endDate->getUnixTime()) continue;

                $repeatDates[] = Vhmis_Date::fromTimeToSQL($time, false);
            }

            // Tháng kế tiếp
            $this->_runDate->addMonth($rule['freq']);
        }

        return $repeatDates;
    }

    /**
     * Tính thời gian lặp lại theo tần suất năm
     * Rule là mảng như sau
     * 'freq' => Tần suất (năm) cho 1 đợt thực hiện
     * 'month' => Tháng xảy ra sự kiện theo năm
     * 'type' => Loại lặp lại (lặp theo ngày trong tháng, hoặc lặp theo ngày trong tuần)
     * 'weekday' => Thứ ngày xáy ra sự kiện trong tuần của sự kiện
     * 'weekday_position' => Vị trí tuần xảy ra sự kiện (0 là mỗi tuần, 5 cuối cùng của tháng, 1-4 là vị trí)
     * 'monthday' => Ngày xảy ra sự kiện trong tháng
     *
     * @param array $rule Luật lặp lại
     * @return array Các ngày lặp lại
     */
    public function calculateYearlyRepeat($rule)
    {
        // Mảng chứa ngày trả về
        $repeatDates = array();

        // Kiểm tra
        if(!isset($rule['freq']) || !is_numeric($rule['freq'])) return $repeatDates;

        // Tính năm lặp tiếp theo
        if($this->_baseDate->getUnixTime() < $this->_nowDate->getUnixTime()) // Nếu ngày hiện tại muộn hơn ngày gốc
        {
            // Khoảng cách
            $year = abs($this->_nowDate->differentYear($this->_baseDate));

            // Tính khoảng cách giữa năm hiện tại với năm xảy ra sự kiện kế tiếp
            $offset = $year >= $rule['freq'] ? $rule['freq'] - ($year % $rule['freq']) : $rule['freq'] - $year;
            if($offset == $rule['freq']) $offset = 0;

            // Thiết lập
            $this->_runDate->time($this->_nowDate->toSQL(false));
            $this->_runDate->addYear($offset);
        }
        else // Nếu ngày hiện tại sớm hơn ngày gốc
        {
            $this->_runDate->time($this->_baseDate->toSQL(false));
        }

        // Trả về ngày tiên của năm
        $this->_runDate = $this->_runDate->startDateOfYear('Vhmis_Date'); // 01.01.01

        // Thực hiện tìm các ngày lặp cho đến khi chạm mốc thời gian dừng
        while($this->_runDate->differentDay($this->_endDate) >= 0)
        {
            foreach($rule['month'] as $month)
            {
                // Đi đến tháng
                $this->_runDate->setMonth($month);

                // Đi đến ngày
                // Nếu lặp theo ngày trong tuần, trước hết ta tìm các ngày trong tháng tương ứng của nó
                // và lưu vào giá trị của $rule['monthday']
                if($rule['type'] == 1)
                {
                    $days = $this->_runDate->daysOfWeekdayInMonth($rule['weekday'], $rule['weekday_position']);
                    if(is_array($days)) $rule['monthday'] = $days;
                    else $rule['monthday'] = array($days);
                }

                // Duyệt các ngày xảy ra sự kiện trong trang
                foreach($rule['monthday'] as $day)
                {
                    // Tính ngày lệch của ngày xảy ra sự kiện với ngày đầu tháng
                    $days = $day - 1;

                    $time = $this->_runDate->getUnixTime() + $days * Vhmis_Date::DAYTOSECOND;

                    // Vẫn có trường hợp thời gian xảy ra trước thời gian hiện tại và sau thời gian dừng
                    if($time < $this->_nowDate->getUnixTime() || $time > $this->_endDate->getUnixTime()) continue;

                    $repeatDates[] = Vhmis_Date::fromTimeToSQL($time, false);
                }
            }

            // Năm kế tiếp
            $this->_runDate->addYear($rule['freq']);
            $this->_runDate = $this->_runDate->startDateOfYear('Vhmis_Date');
        }

        return $repeatDates;
    }
}