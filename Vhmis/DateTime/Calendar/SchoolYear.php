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
     *
     * @param array $timetable
     * @return \Vhmis\DateTime\Calendar\SchoolYear
     */
    public function setTimetable($timetable)
    {
        $this->timetable = $timetable;

        return $this;
    }

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
}
