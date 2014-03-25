<?php

namespace Vhmis\DateTime\Calendar;

use \Vhmis\DateTime\DateTime;

abstract class CalendarAbstract implements CalendarInterface
{
    /**
     * Ngày bắt đầu
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $startDate;

    /**
     * Ngày kết thúc
     *
     * @var \Vhmis\DateTime\DateTime
     */
    protected $endDate;

    /**
     * Thiết lập ngày bắt đầu
     *
     * @param string $date
     * @return \Vhmis\DateTime\Calendar\CalendarAbstract
     */
    public function setStartDate($date)
    {
        $this->startDate = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');

        return $this;
    }

    /**
     * Thiết lập ngày kết thúc
     *
     * @param string $date
     * @return \Vhmis\DateTime\Calendar\CalendarAbstract
     */
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
        return $this->getMonth($this->endDate);
    }

    /**
     * Lấy số tuấn trải qua trong lịch thường giữa ngày bắt đầu và ngày kết thúc
     *
     * @return int
     */
    public function getTotalWeek()
    {
        return $this->getWeek($this->endDate);
    }

    /**
     * Lấy số ngày trải qua trong lịch thường giữa ngày bắt đầu và ngày kết thúc
     *
     * @return int
     */
    public function getTotalDay()
    {
        return $this->startDate->diff($this->endDate)->days;
    }

    /**
     * Lấy \DateInterval giữa ngày bắt đầu và ngày kết thúc
     *
     * @return \DateInterval
     */
    public function getDateTimeInterval()
    {
        return $this->startDate->diff($this->endDate);
    }

    /**
     * Lấy tuần thứ theo lịch của 1 ngày
     *
     * @param \Vhmis\DateTime\DateTime|string $date Ngày
     * @return int
     */
    public function getWeek($date)
    {
        if (is_string($date)) {
            $dateString = $date;
            $date = new DateTime();
            $date->modify($dateString);
        }

        if (!($date instanceof DateTime)) {
            return null;
        }

        $diffWeek = $this->startDate->diffWeek($date);

        return $diffWeek + 1;
    }

    /**
     * Lấy tháng thứ theo lịch của 1 ngày
     *
     * @param \Vhmis\DateTime\DateTime|string $date
     * @return int
     */
    public function getMonth($date)
    {
        if (is_string($date)) {
            $dateString = $date;
            $date = new DateTime();
            $date->modify($dateString);
        }

        if (!($date instanceof DateTime)) {
            return null;
        }

        $diffMonth = $this->startDate->diffMonth($date);

        return $diffMonth + 1;
    }
}
