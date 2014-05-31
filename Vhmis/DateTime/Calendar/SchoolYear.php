<?php

namespace Vhmis\DateTime\Calendar;

use \Vhmis\DateTime\DateTime;

class SchoolYear extends CalendarAbstract
{
    protected $timetable = array(
        array('07:00', '07:50'),
        array('07:55', '08:45'),
        array('08:50', '09:40'),
        array('09:45', '10:35'),
        array('10:40', '11:30'),
        array('12:30', '13:20'),
        array('13:25', '14:15'),
        array('14:20', '15:10'),
        array('15:15', '16:05'),
        array('16:10', '17:00')
    );

    /**
     * Set time table
     *
     * @param array $timetable
     *
     * @return \Vhmis\DateTime\Calendar\SchoolYear
     */
    public function setTimetable($timetable)
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * Get calendar date info
     *
     * @param string|\Vhmis\DateTime\DateTime $date
     *
     * @return array
     */
    public function getDateInfo($date)
    {
        if (is_string($date)) {
            $dateString = $date;
            $date = new DateTime();
            $date->modify($dateString);
        }

        if (!($date instanceof DateTime)) {
            return null;
        }

        $info = array();

        $info['week'] = $this->getWeek($date);
        $info['month'] = $this->getMonth($date);
        $info['weekday'] = $date->getWeekday();
        $info['period'] = $this->getPeriod($date->format('H:i'));

        return $info;
    }

    /**
     * Get period
     *
     * @param  string $time hh:mm
     *
     * @return int
     */
    public function getPeriod($time)
    {
        $current = 1;

        foreach ($this->timetable as $times) {
            if ($times[0] > $time) {
                return $current;
            }

            if ($times[1] < $time) {
                $current++;
                continue;
            }

            return $current;
        }

        return $current;
    }

    /**
     * Get original date
     *
     * @param int $week        Tuần thứ
     * @param int $weekday     Ngày thứ (2-8 monday - sunday)
     * @param int $startPeriod Tiết bắt đầu
     * @param int $endPeriod   Tiết kết thúc
     *
     * @return array
     */
    public function getOriginalDate($week, $weekday, $startPeriod = null, $endPeriod = null)
    {
        if ($weekday == 0) {
            $weekday = 8;
        }

        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->startDate->formatISO(1));
        $date->addWeek($week - 1);
        $date->modify('monday this week')->addDay($weekday - 2);

        $startTime = '';
        $endTime = '';

        if ($startPeriod !== null && isset($this->timetable[($startPeriod - 1)])) {
            $startTime = $this->timetable[($startPeriod - 1)][0] . ':00';
            $endTime = $this->timetable[($startPeriod - 1)][1] . ':00';
        }

        if ($endPeriod !== null && isset($this->timetable[($endPeriod - 1)])) {
            $endTime = $this->timetable[($endPeriod - 1)][1] . ':00';
        }

        return array(
            'date'      => $date->formatISO(0),
            'startTime' => $startTime,
            'endTime'   => $endTime
        );
    }
}
