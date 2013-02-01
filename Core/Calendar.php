<?php

class Vhmis_Calendar
{

    protected $_startOfWeek = 1; // thứ 2
    
    /**
     * Đối tượng Vhmis_Date, dùng để tính toán ngày
     */
    protected $_date;

    public function __construct()
    {
        $this->_date = new Vhmis_Date();
    }

    /**
     * Tìm ngày trong một tuần
     */
    public function datesOfWeekByWeekNumber($week, $year)
    {
        //
        $dates = array();
        
        // Ngày đầu tiên
        $this->_date->time($year . '-W' . $week . '-1');
        $date = $this->_date->toArray();
        $dates[$date['year']][$date['month']][] = $date;
        for ($i = 0; $i < 6; $i ++) {
            $this->_date->addDay(1);
            $date = $this->_date->toArray();
            $dates[$date['year']][$date['month']][] = $date;
        }
        
        return $dates;
    }

    /**
     * Tìm ngày trong một tháng
     */
    public function datesOfMonth($month, $year, $fix = true)
    {
        $m = (int) $month;
        if ($m < 10)
            $month = '0' . $m;
        $y = $year;
        
        $dates = array();
        
        // Ngày đầu tiên của tháng
        $firstDate = $this->_date->time($y . '-' . $m . '-01');
        
        // Số ngày của tháng
        $total = $this->_date->daysInMonth();
        
        // Tuần của ngày đầu tiên
        $week = $this->_date->getWeekOfYear();
        
        // Thứ của ngày đầu tiên
        $day = $this->_date->getWeekday();
        
        // Tìm tất cả các ngày của tháng trước cùng tuần với ngày đầu tiên của
        // tháng
        if ($fix && $day != 1) {
            // Tìm số ngày của tháng trước
            list ($mP, $yP) = $this->_date->getPrevMonth();
            $dayP = Vhmis_Date::getDaysInMonth($mP, $yP);
            
            if ($mP < 10)
                $mP = '0' . $mP;
            for ($i = 1; $i < $day; $i ++) {
                $dates[$yP][$mP][] = array('date' => $this->outputIso(($dayP - $day + $i + 1), $mP, $yP), 'week' => Vhmis_Utility_String::addZero($week, 2), 'wday' => $i);
            }
        }
        
        // Các ngày trong tháng
        for ($i = 1; $i <= $total; $i ++) {
            $dates[$year][$month][] = array('date' => $this->outputIso($i, $month, $year), 'week' => Vhmis_Utility_String::addZero($week, 2), 'wday' => $day);
            
            if ($day == 7) {
                $day = 1;
                // Tuần đầu tiên của năm có thể là tuần cuối của năm trước!!!
                // http://en.wikipedia.org/wiki/ISO_week_date
                if ($m == 1 && $week == 52 || $week == 53) {
                    $week = 1;
                }                 // Tuần cuối cùng của năm có thể là tuần 1 của năm kế tiếp!!!
                else 
                    if ($m == 12) {
                        $week = date('W', strtotime($year . '-12-' . ($i + 1)));
                    } else
                        $week ++;
            } else {
                $day ++;
            }
        }
        
        // Các ngày của tháng kế tiếp trùng với tuần của ngày cuối cùng của
        // tháng hiện tại
        if ($fix && $day != 1) {
            list ($mN, $yN) = $this->_date->getNextMonth();
            if ($mN < 10)
                $mN = '0' . $mN;
            for ($i = $day; $i <= 7; $i ++) {
                $dates[$yN][$mN][] = array('date' => $this->outputIso($i - $day + 1, $mN, $yN), 'week' => Vhmis_Utility_String::addZero($week, 2), 'wday' => $i);
            }
        }
        
        return $dates;
    }

    public function outputIso($day, $month, $year)
    {
        $day = (int) $day;
        $month = (int) $month;
        if ($day < 10)
            $day = '0' . $day;
        if ($month < 10)
            $month = '0' . $month;
        
        return $year . '-' . $month . '-' . $day;
    }
}