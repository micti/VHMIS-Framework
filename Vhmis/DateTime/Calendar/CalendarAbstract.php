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

    /**
     * Thiết lập ngày kết thúc
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
     * Thiết lập ngày bắt đầu
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

    public function getWeek($date)
    {
        if(is_string($date)) {
            $dateString = $date;
            $date = new DateTime();
            $date->modify($dateString);
        }

        if(!($date instanceof DateTime)) {
            return null;
        }

        $diffWeek = $this->startDate->diffWeek($date);

        return $diffWeek + 1;
    }

    public function getMonth($date)
    {
        if(is_string($date)) {
            $dateString = $date;
            $date = new DateTime();
            $date->modify($dateString);
        }

        if(!($date instanceof DateTime)) {
            return null;
        }

        $diffMonth = $this->startDate->diffMonth($date);

        return $diffMonth + 1;
    }
}
