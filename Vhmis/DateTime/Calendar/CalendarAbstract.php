<?php

namespace Vhmis\DateTime\Calendar;

use \Vhmis\DateTime\DateTime;

abstract class CalendarAbstract implements CalendarInterface
{
    /**
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $startDate;

    /**
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $endDate;

    /**
     *
     * @param string $date
     */
    protected $startDateString;

    public function setStartDate($date)
    {
        $this->startDate = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
        return $this;
    }

    public function setEndDate($date)
    {
        $this->endDate = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
        return $this;
    }

    /**
     * Lấy số tháng trải qua trong lịch thường giữa ngày bắt đầu và ngày kết thúc
     *
     * @return int
     */
    public function getTotalMonth()
    {
        if($this->startDate->getYear() !== $this->endDate->getYear())
        {
            $totalMonth = 12 - $this->startDate->getMonth() + 1; // Số tháng trong năm đầu tiên
            $totalMonth += $this->endDate->getMonth(); // Số tháng trong năm cuối cùng
            $totalMonth += ($this->endDate->getYear() - $this->startDate->getYear() - 1) * 12; // Số tháng trong các năm ở giữa

            return $totalMonth;
        }

        return $this->endDate->getMonth() - $this->startDate->getMonth() + 1;
    }

    /**
     * Lấy số tuấn trải qua trong lịch thường giữa ngày bắt đầu và ngày kết thúc
     *
     * @return int
     */
    public function getTotalWeek()
    {
        // Trả về ngày đầu tuần trong tuần của ngày đầu tiên trong lịch
        $start = $this->startDate->getTimestamp();
        $this->startDate->modify('Monday this week');

        // Trả về ngày đầu tuần trong tuần của ngày cuối cùng trong lịch
        $end = $this->endDate->getTimestamp();
        $this->endDate->modify('Monday this week');

        $totalWeek = $this->getTotalDay() / 7; // Always int =)) trust me

        // Rollback
        $this->startDate->setTimestamp($start);
        $this->endDate->setTimestamp($end);

        return $totalWeek;
    }

    public function getTotalDay()
    {
        return $this->startDate->diff($this->endDate)->days;
    }

    /**
     *
     * @return \DateInterval
     */
    public function getDateTimeInterval()
    {
        return $this->startDate->diff($this->endDate);
    }
}
