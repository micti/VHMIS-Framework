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
    const REPEAT_BASED_ON_WDAY = 1;

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
    protected $weekday = array(2, 3, 4, 5, 6); // Monday to Thursday

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

    /**
     * Định nghĩa ngày nghỉ
     *
     * @var array
     */
    protected $weekend = array(1, 7); // Sunday, Saturday

    public function __construct()
    {
        $this->objDate = new DateTime();
        $this->objDateEnd = new DateTime();
        $this->objDateRangeBegin = new DateTime();
        $this->objDateRangeEnd = new DateTime();
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
        if (isset($info['freq'])) {
            $this->freq = (int) $info['freq'];
        }

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
        $this->freq = $info['freq'];
        $this->wday = is_string($info['wday']) ? explode(',', $info['wday']) : $info['wday'];

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
        $this->freq = $info['freq'];
        $this->base = $info['base'];
        $this->day = is_string($info['day']) ? explode(',', $info['day']) : $info['day'];
        $this->wday = $info['wday'];
        $this->wdayPosition = $info['wday_position'];

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

        $this->freq = $info['freq'];
        $this->month = is_string($info['month']) ? explode(',', $info['month']) : $info['month'];
        $this->base = $info['base'];
        $this->day = $info['day'];
        $this->wday = $info['wday'];
        $this->wdayPosition = $info['wday_position'];

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

        return $dates;
    }

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
}
