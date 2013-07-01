<?php

namespace Vhmis\DateTime;

class DateRepeat
{
    /**
     * Lặp lại theo ngày
     */
    const REPEAT_TYPE_DAILY = 4;

    /**
     * Lặp lại theo tuần
     */
    const REPEAT_TYPE_WEEKLY = 5;

    /**
     * Lặp lại theo tháng
     */
    const REPEAT_TYPE_MONTHLY = 6;

    /**
     * Lặp lại theo năm
     */
    const REPEAT_TYPE_YEARLY = 7;

    /**
     * Cách chọn ngày lặp lại dựa trên ngày trong tháng
     */
    const REPEAT_BASED_ON_DAY = 1;

    /**
     * Cách chọn ngày lặp lại dựa trên ngày trong tuần
     */
    const REPEAT_BASED_ON_WDAY = 2;

    /**
     * Ngày bắt đầu
     *
     * @var type
     */
    protected $dateBegin;

    /**
     * Ngày kết thúc (nếu có)
     *
     * @var type
     */
    protected $dateEnd;

    /**
     * Số lần xảy ra để kết thúc (nếu có)
     *
     * @var int
     */
    protected $timesEnd;

    /**
     * Tần suất lặp lại
     *
     * @var int
     */
    protected $freq;

    /**
     * Lấy lặp lại dựa theo ngày trong tháng (1) hoặc ngày trong tuần (2)
     *
     * @var int
     */
    protected $base;

    /**
     * Ngày hoặc các ngày trong tuần xảy ra lặp lại
     *
     * Dùng trong lặp lại theo tuần, tháng, năm
     *
     * @var array|int
     */
    protected $wday;

    /**
     * Vị trí của ngày trong tháng
     *
     * 1 => đầu tiên, 2, 3, 4=> Thứ 4, 5=> Cuối cùng
     *
     * @var array|int
     */
    protected $wdayPosition;

    /**
     * Ngày hoặc các ngày trong tháng xảy ra lặp lại
     *
     * Dùng trong lặp lại theo tháng, năm
     *
     * @var array|int
     */
    protected $day;

    /**
     * Các tháng trong tuần xảy ra lặp lại
     *
     * Dùng trong lặp lại theo năm
     *
     * @var array
     */
    protected $month;

    /**
     * Định nghĩa ngày đi làm weekday, workday ...
     *
     * @var array
     */
    protected $weekday = array(2, 3, 4, 5, 6); // Monday to Friday

    /**
     * Đối tượng DateTime, dùng để xử lý thời gian
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $objDate;

    /**
     * Đối tượng DateTime, dùng để quản lý thời gian kết thúc
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $objDateEnd;

    /**
     * Đối tượng DateTime, dùng để quản lý mốc thời gian bắt đầu cần lấy
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $objDateRangeBegin;

    /**
     * Đối tượng DateTime, dùng để quản lý mốc thời gian kết thúc cần lấy
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $objDateRangeEnd;
    protected $startDateOfWeek = 'monday';
    protected $allday = array(
        '1' => 'sunday',
        '2' => 'monday',
        '3' => 'tuesday',
        '4' => 'wednesday',
        '5' => 'thursday',
        '6' => 'friday',
        '7' => 'saturday',
        '8' => 'sunday'
    );
    protected $positions = array(
        '1' => 'first',
        '2' => 'second',
        '3' => 'third',
        '4' => 'fourth',
        '5' => 'last'
    );

    /**
     * Định nghĩa ngày nghỉ
     *
     * @var array
     */
    protected $weekend = array(7, 8); // Sunday, Saturday

    public function __construct()
    {
        $this->objDate = new DateTime();
        $this->objDateEnd = new DateTime();
        $this->objDateRangeBegin = new DateTime();
        $this->objDateRangeEnd = new DateTime();
    }

    /**
     * Reset lại các thông số
     *
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function reset()
    {
        $this->dateBegin = null;
        $this->dateEnd = null;
        $this->freq = null;
        $this->timesEnd = null;

        return $this;
    }

    /**
     * Thiết lập ngày đầu tiên
     *
     * @param type $dateBegin
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setDateBegin($dateBegin)
    {
        if ($this->objDate->modify($dateBegin)) {
            $this->dateBegin = $dateBegin;
        }

        return $this;
    }

    /**
     * Thiết lập ngày kết thúc lặp lại (nếu có)
     *
     * @param type $dateEnd
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setDateEnd($dateEnd)
    {
        if ($this->objDateEnd->modify($dateEnd)) {
            $this->dateEnd = $dateEnd;
        }

        return $this;
    }

    /**
     * Thiết lập số lần xảy ra lập lại để kết thúc (nếu có)
     *
     * @param int $dateEnd
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setTimesEnd($timesEnd)
    {
        $this->timesEnd = $timesEnd;

        return $this;
    }

    /**
     * Thiết lập ngày đi làm
     *
     * @param array $weekday
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;

        return $this;
    }

    /**
     * Thiết lập ngày nghỉ
     *
     * @param array $weekend
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setWeekend($weekend)
    {
        $this->weekend = $weekend;

        return $this;
    }

    /**
     * Thiết lập kiểu lặp lại (theo ngày, theo tuần, theo tháng, theo năm)
     *
     * @param int $type
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setRepeatType($type)
    {
        $this->type = (int) $type;

        return $this;
    }

    /**
     * Thiết lập thông tin lặp lại
     *
     * @param array $info
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setRepeatInfo($info)
    {
        if ($this->type === static::REPEAT_TYPE_DAILY) {
            return $this->setDailyRepeatInfo($info);
        }

        if ($this->type === static::REPEAT_TYPE_WEEKLY) {
            return $this->setWeeklyRepeatInfo($info);
        }

        if ($this->type === static::REPEAT_TYPE_MONTHLY) {
            return $this->setMonthlyRepeatInfo($info);
        }

        if ($this->type === static::REPEAT_TYPE_YEARLY) {
            return $this->setYearlyRepeatInfo($info);
        }

        return $this;
    }

    /**
     * Thiết lập thông tin lặp lại theo ngày
     *
     * Các thông số
     * 'freq' => Tần suất cho mỗi lần lặp lại (ví dụ freq = 3 -> 2 ngày 1 lần)
     *
     * @param array $info
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setDailyRepeatInfo($info)
    {
        $this->freq = (int) $info['freq'];

        return $this;
    }

    /**
     * Thiết lập thông tin lặp lại theo tuần
     *
     * Các thông số
     * 'freq' => Tần suất cho mỗi lần lặp lại (ví dụ freq = 2 -> 2 tuần 1 lần)
     * 'wday' => Các ngày trong tuần xảy ra lặp lại (1 là CN, 7 là thứ 7) Có thể là mảng hoặc chuỗi cách nhau bằng dấu ,
     *
     * @param array $info
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setWeeklyRepeatInfo($info)
    {
        $this->freq = (int) $info['freq'];
        $this->wday = is_string($info['wday']) ? explode(',', $info['wday']) : $info['wday'];

        // Sắp xếp wday
        foreach ($this->wday as &$w) {
            $w = (int) $w;
            if ($w === 1 && $this->startDateOfWeek === 'monday') {
                $w = 8;
            }
        }

        sort($this->wday);

        return $this;
    }

    /**
     * Thiết lập thông tin lặp lại theo tháng
     *
     * Các thông số
     * 'freq' => Tần suất cho mỗi lần lặp lại (ví dụ freq = 5 -> 5 tháng 1 lần)
     * 'base' => 1 hoặc 2, nếu 1 thì chọn day, nếu 2 thì chọn wday
     * 'day' => Các ngày trong tháng xảy ra lặp lại (1->31) Có thể là mảng hoặc chuỗi cách nhau bằng dấu ,
     * 'wday' => Ngày trong tuần xảy ra lặp lại (1 là CN, 7 là thứ 7, 0 là ngày bất kỳ, 8 là ngày làm việc, 9 là ngày cuối tuần)
     * 'wday_position' => Vị trí của ngày trong tuần trong tháng (1 là đầu tiên, 4 là thứ 4, 5 là cuối cùng)
     *
     * 'wday = 0' kết hợp với 'wday_position = 5' có nghĩa là ngày cuối cùng của tháng
     *
     * @param array $info
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setMonthlyRepeatInfo($info)
    {
        $this->freq = (int) $info['freq'];
        $this->base = (int) $info['base'];

        if ($this->base === 1) {
            $this->day = is_string($info['day']) ? explode(',', $info['day']) : $info['day'];
        } else if ($this->base === 2) {
            $this->wday = (int) $info['wday'];
            $this->wdayPosition = (int) $info['wday_position'];
        }


        return $this;
    }

    /**
     * Thiết lập thông tin lặp lại theo năm
     *
     * Các thông số
     * 'freq' => Tần suất cho mỗi lần lặp lại (ví dụ freq = 1 -> 1 năm 1 lần)
     * 'month' => Các tháng trong năm xảy ra lặp lại (1->12) Có thể là mảng hoặc chuỗi cách nhau bằng dấu ,
     * 'base' => 1 hoặc 2, nếu 1 thì chọn day, nếu 2 thì chọn wday
     * 'day' => Ngày trong tháng xảy ra lặp lại (1-29,30,31)
     * 'wday' => Ngày trong tuần xảy ra lặp lại (1 là CN, 7 là thứ 7, 0 là ngày bất kỳ, 8 là ngày làm việc, 9 là ngày cuối tuần)
     * 'wday_position' => Vị trí của ngày trong tuần trong tháng (1 là đầu tiên, 4 là thứ 4, 5 là cuối cùng)
     *
     * 'wday = 0' kết hợp với 'wday_position = 5' có nghĩa là ngày cuối cùng của tháng
     *
     * @param array $info
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setYearlyRepeatInfo($info)
    {

        $this->freq = (int) $info['freq'];
        $this->month = is_string($info['month']) ? explode(',', $info['month']) : $info['month'];
        $this->base = (int) $info['base'];
        $this->day = (int) $info['day'];
        $this->wday = (int) $info['wday'];
        $this->wdayPosition = (int) $info['wday_position'];

        return $this;
    }

    /**
     *
     */
    public function findRepeat($dateRangeBegin, $dateRangeEnd)
    {
        // Mảng chứa các ngày lặp lại
        $dates = array();

        // Reset objDate, về lại mốc thời gian ngày bắt đầu
        $this->objDate->modify($this->dateBegin);

        // Thiết lập
        $this->objDateRangeBegin->modify($dateRangeBegin);
        $this->objDateRangeEnd->modify($dateRangeEnd);

        // So sánh các mốc
        if ($this->objDate > $this->objDateRangeEnd) {
            return $dates;
        }

        /* Tìm theo ngày */
        if ($this->type === static::REPEAT_TYPE_DAILY) {
            return $this->findDailyRepeat();
        }

        /* Tìm theo tuần */
        if ($this->type === static::REPEAT_TYPE_WEEKLY) {
            if ($this->timesEnd !== null) {
                $this->dateEnd = $this->weeklyRepeatTimesToDateStop();
                $this->objDateEnd->modify($this->dateEnd);
            }
            return $this->findWeeklyRepeat();
        }

        return $dates;
    }

    /**
     * Tìm các ngày lặp lại theo ngày
     *
     * @return array
     */
    protected function findDailyRepeat()
    {
        $dates = array();
        $times = 0;

        $useTimesToStop = $this->timesEnd !== null ? true : false;
        $useDateToStop = $this->dateEnd !== null ? true : false;

        // Nếu ngày bắt đầu bé hơn ngày bắt đầu của vùng cần lấy
        if ($this->objDate < $this->objDateRangeBegin) {
            $diff = $this->objDate->diffDay($this->objDateRangeBegin);
            $times = $diff % $this->freq === 0 ? $diff / $this->freq : floor($diff / $this->freq) + 1; // +1 là tính luôn mốc thời gian ban đầu

            if ($useTimesToStop && $times >= $this->timesEnd) {
                return $dates;
            }

            // Di chuyển đến mốc thời gian tiếp theo xảy ra sự kiện
            $this->objDate->modify('+ ' . ($times * $this->freq) . ' days');
        }

        while ($this->objDate <= $this->objDateRangeEnd) {
            if ($useTimesToStop && $times >= $this->timesEnd) {
                return $dates;
            }

            if ($useDateToStop && $this->objDate > $this->objDateEnd) {
                return $dates;
            }

            $dates[] = $this->objDate->formatISO(0);
            $times++;
            $this->objDate->modify('+ ' . $this->freq . ' days');
        }

        return $dates;
    }

    /**
     * Tìm các ngày lặp lại theo tuần
     *
     * @return array
     */
    protected function findWeeklyRepeat()
    {
        $dates = array();
        $times = 0;

        // Reset objDate, về lại mốc thời gian ngày bắt đầu
        $this->objDate->modify($this->dateBegin);

        $useDateToStop = $this->dateEnd !== null ? true : false;

        if ($this->objDate < $this->objDateRangeBegin) {
            $diff = $this->objDate->diffWeek($this->objDateRangeBegin);
            $skip = ceil($diff / $this->freq) * $this->freq;
            $this->objDate->modify('+ ' . $skip . ' weeks');
        }

        while (true) {
            // Liệt kê hết các ngày lặp lại trong tuần
            foreach ($this->wday as $w) {
                $this->objDate->modify($this->allday[$w] . ' this week');

                // Kết thúc nếu đã vượt quá 1 trong 2 giới hạn
                if ($this->objDate > $this->objDateRangeEnd) {
                    return $dates;
                }
                if ($useDateToStop && $this->objDate > $this->objDateEnd) {
                    return $dates;
                }

                // Bỏ qua nếu vẫn chưa vào range
                if ($this->objDate < $this->objDateRangeBegin) {
                    continue;
                }

                // Thêm vào danh sách lặp lại
                $dates[] = $this->objDate->formatISO(0);
            }

            // Nhảy đến tuần tiếp theo
            $this->objDate->modify('+ ' . $this->freq . ' weeks');
        }

        return $dates;
    }

    /**
     * Chuyển đối số lần dừng lại thành ngày dừng lại
     *
     * @return string
     */
    public function timesToDateStop()
    {
        /* theo tuần */
        if ($this->type === static::REPEAT_TYPE_WEEKLY) {
            return $this->weeklyRepeatTimesToDateStop();
        }

        /* theo tháng */
        if ($this->type === static::REPEAT_TYPE_MONTHLY) {
            return $this->monthlyRepeatTimesToDateStop();
        }
    }

    /**
     * Tìm ngày cuối cùng trong lặp lại theo tuần dựa trên số lần lặp lại
     */
    protected function weeklyRepeatTimesToDateStop()
    {
        if ($this->timesEnd === null) {
            return null;
        }

        // Reset
        $this->objDate->modify($this->dateBegin);

        $times = $this->timesEnd;

        $wdays = $this->wday;

        // Tìm số lần lặp lại của tuần đầu tiên
        $wday = 1 + (int) $this->objDate->format('N');
        if ($wday === 8 && $this->startDateOfWeek !== 'monday') {
            $wday = 1;
        }

        foreach ($wdays as $w) {
            if ($w >= $wday) {
                $times--;
                $this->objDate->modify($this->allday[$w] . ' this week');
                if ($times === 0) {
                    return $this->objDate->formatISO(0);
                }
            }
        }

        // Các tuần tiếp theo
        $weeks = floor($times / count($wdays)) * $this->freq;

        // Số lần của tuần cuối cùng
        $timesLastWeek = $times % count($wdays);

        if ($weeks !== 0) {
            $this->objDate->modify('+ ' . $weeks . ' weeks');
        }

        if ($timesLastWeek === 0) {
            return $this->objDate->modify($this->allday[end($wdays)] . ' this week')->formatISO(0);
        }

        // Đến tuần cuối cùng
        $this->objDate->modify('+ ' . $this->freq . ' week');
        foreach ($wdays as $w) {
            $timesLastWeek--;
            $this->objDate->modify($this->allday[$w] . ' this week');
            if ($timesLastWeek === 0) {
                return $this->objDate->formatISO(0);
            }
        }
    }

    /**
     * Tìm ngày cuối cùng trong lặp lại theo tuần dựa trên số lần lặp lại
     */
    protected function monthlyRepeatTimesToDateStop()
    {
        if ($this->timesEnd === null) {
            return null;
        }

        // Reset
        $this->objDate->modify($this->dateBegin);

        $times = $this->timesEnd;

        // Tìm số lần lặp lại của tháng đầu tiên
        $day = (int) $this->objDate->format('j');

        // Lặp lại theo ngày trong tháng
        if ($this->base === static::REPEAT_BASED_ON_DAY) {
            $days = $this->day;

            foreach ($days as $d) {
                if ($d >= $day) {
                    $times--;
                    $this->objDate->setDay($d);
                    if ($times === 0) {
                        return $this->objDate->formatISO(0);
                    }
                }
            }

            // Các tuần tiếp theo
            $months = floor($times / count($days)) * $this->freq;

            // Số lần của tuần cuối cùng
            $timesLastMonth = $times % count($days);

            if ($months !== 0) {
                $this->objDate->addMonth($months);
            }

            if ($timesLastMonth === 0) {
                return $this->objDate->setDay(end($days))->formatISO(0);
            }

            // Đến tháng cuối cùng
            $this->objDate->addMonth($this->freq);

            foreach ($days as $d) {
                $timesLastMonth--;
                $this->objDate->setDay($d);
                if ($timesLastMonth === 0) {
                    return $this->objDate->formatISO(0);
                }
            }
        }

        // Lặp lại theo ngày trong tuần
        if ($this->base === static::REPEAT_BASED_ON_WDAY) {

            $this->objDate->addMonth(($times - 1) * $this->freq);

            if ($this->wday > 0 & $this->wday < 8) { // Thứ 2 đến Chủ nhật
                $this->objDate->modify($this->positions[$this->wdayPosition] . ' ' . $this->allday[$this->wday] . ' of this month');
                return $this->objDate->formatISO(0);
            } else if ($this->wday == 0) { // Một ngày bất kỳ
                if ($this->wdayPosition < 5) {
                    return $this->objDate->setDay($this->wdayPosition)->formatISO(0);
                } else { // Ngay cuoi cung
                    return $this->objDate->modify('last day of this month')->formatISO(0);
                }
            } else if ($this->wday === 8) { // Cac ngay di lam
                if ($this->wdayPosition === 1) {
                    // Tìm ngày đi làm đầu tiên của tháng
                    $wk = 31;
                    foreach ($this->weekday as $wken) {
                        $wkd = (int) $this->objDate->modify('first ' . $this->allday[$wken] . ' of this month')->format('j');
                        if ($wkd < $wk) {
                            $wk = $wkd;
                        }
                    }
                    return $this->objDate->setDay($wk)->formatISO(0);
                } else {
                    // Tìm ngày đi làm cuối cùng của tháng
                    $wk = 0;
                    foreach ($this->weekday as $wken) {
                        $wkd = (int) $this->objDate->modify('last ' . $this->allday[$wken] . ' of this month')->format('j');
                        if ($wkd > $wk) {
                            $wk = $wkd;
                        }
                    }
                    return $this->objDate->setDay($wk)->formatISO(0);
                }
            } else { // Ngày nghỉ
                if ($this->wdayPosition === 1) {
                    // Tìm ngày nghỉ đầu tiên của tháng
                    $wk = 31;
                    foreach ($this->weekend as $wken) {
                        $wkd = (int) $this->objDate->modify('first ' . $this->allday[$wken] . ' of this month')->format('j');
                        if ($wkd < $wk) {
                            $wk = $wkd;
                        }
                    }
                    return $this->objDate->setDay($wk)->formatISO(0);
                } else {
                    // Tìm ngày nghỉ cuối cùng của tháng
                    $wk = 0;
                    foreach ($this->weekend as $wken) {
                        $wkd = (int) $this->objDate->modify('last ' . $this->allday[$wken] . ' of this month')->format('j');
                        if ($wkd > $wk) {
                            $wk = $wkd;
                        }
                    }
                    return $this->objDate->setDay($wk)->formatISO(0);
                }
            }
        }

        return null;
    }
}
