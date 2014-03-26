<?php

namespace Vhmis\DateTime\Calendar;

use \Vhmis\DateTime\DateTime;

class SchoolYear extends CalendarAbstract
{
    protected $timetable = [
        ['07:00', '07:50'],
        ['07:55', '08:45'],
        ['08:50', '09:40'],
        ['09:45', '10:35'],
        ['10:40', '11:30'],
        ['12:30', '13:20'],
        ['13:25', '14:15'],
        ['14:20', '15:10'],
        ['15:15', '16:05'],
        ['16:10', '17:00']
    ];

    /**
     * Thiết lập thời gian biểu
     *
     * @param array $timetable
     * @return \Vhmis\DateTime\Calendar\SchoolYear
     */
    public function setTimetable($timetable)
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * Lấy thông tin về ngày theo lịch năm học
     *
     * @param string|\Vhmis\DateTime\DateTime $date
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

        $info['week'] = $this->getWeek($date);
        $info['month'] = $this->getMonth($date);
        $info['weekday'] = $date->getWeekDay();
        $info['period'] = $this->getPeriod($date->format('H:i'));

        return $info;
    }

    /**
     * Lấy tiết học ứng với thời gian
     *
     * @param string $time
     * @return int
     */
    public function getPeriod($time)
    {
        $current = 1;

        foreach ($this->timetable as $times) {
            if (DateTime::compareTime($times[0], $time) === 1) {
                return $current;
            }

            if (DateTime::compareTime($times[1], $time) === -1) {
                $current++;
                continue;
            }

            return $current;
        }

        return $current;
    }

    /**
     * Lấy ngày thực theo tuần thứ và ngày thứ trong tuần, tiết bắt đầu và tiết kết thúc
     *
     * @param int $week Tuần thứ
     * @param int $weekday Ngày thứ (2-8 monday - sunday)
     * @param int $startPeriod Tiết bắt đầu
     * @param int $endPeriod Tiết kết thúc
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
            $endTime = $this->timetable[($startPeriod - 1)][0] . ':00';
        }

        if ($endPeriod !== null && isset($this->timetable[($endPeriod - 1)])) {
            $endTime = $this->timetable[($endPeriod - 1)][0] . ':00';
        }

        return [
            'date'      => $date->formatISO(0),
            'startTime' => $startTime,
            'endTime'   => $endTime
        ];
    }
}
