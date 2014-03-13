<?php

namespace Vhmis\DateTime;

/**
 * Class dùng để thao tác với lịch dương theo chuẩn ISO-8601
 *
 * Chú ý
 * 1 => Chủ nhât, 2-7 Thứ 2 đến thứ 7
 * Ngày làm việc 2,3,4,5,6
 * Ngày nghỉ 7,1
 * Ngày bắt đầu tuần 2
 * Tuần đầu của năm là tuần có chứa thứ 5 đầu tiên của năm
 */
class CalendarIso
{
    protected $week = array(2, 3, 4, 5, 6, 7, 1);
    protected $weekday = array(2, 3, 4, 5, 6);
    protected $weekend = array(7, 1);
    protected $startweek = 2;
    protected $dayCode = array(
        2 => 'mon',
        3 => 'tue',
        4 => 'wed',
        5 => 'thu',
        6 => 'fri',
        7 => 'sat',
        1 => 'sun'
    );

    /**
     * Lấy danh sách các ngày trong tháng
     *
     * Mảng index bởi
     * ['dates'][year-month][week][date]
     * ['info']
     *
     * @param string $month
     * @param string $year
     * @return array
     */
    public function datesOfMonth($month, $year)
    {
        $oDate = new DateTime;
        $today = new DateTime;

        $oDate->setDate($year, $month, 1);
        $monthyear = $oDate->format('Y-m');
        $daysInMonth = $oDate->format('t');
        $wdayFirstDate = $oDate->format('w') + 1;

        // Add all date of last month and next month that have same week of current month
        $datesOfLastMonth = array_search($wdayFirstDate, $this->week);
        $datesOfNextMonth = 7 - (($datesOfLastMonth + $daysInMonth) % 7);

        $oDate->addDay($datesOfLastMonth * -1);

        $data = array();

        for ($i = 0; $i < $datesOfLastMonth + $daysInMonth + $datesOfNextMonth; $i++) {
            $week = $oDate->format('W');
            $date = $oDate->formatISO(0);
            $data[$monthyear][$week][$date] = array(
                'date' => $date,
                'diff' => array(
                    'day'   => $today->diffDay($oDate),
                    'month' => $today->diffMonth($oDate)
                )
            );
            $oDate->addDay(1);
        }

        return array('dates' => $data, 'info'  => array_values($this->dayCode));
    }

    /**
     * Lấy danh sách các tháng trong khoảng thời gian
     *
     * Trả về mảng
     * ['months'][year-month]
     *
     * @param string $start
     * @param string $end
     * @return array
     */
    public function months($start, $end)
    {
        $oDate1 = new DateTime;
        $oDate2 = new DateTime;
        $oDate2->modify($end);
        $diff = $oDate1->modify($start)->diffMonth($oDate2);

        $data = array();

        for ($i = 0; $i <= $diff; $i++) {
            $monthyear = $oDate1->format('Y-m');
            $data[$monthyear] = $monthyear;
            $oDate1->addMonth(1);
        }

        return array('months' => $data);
    }

    /**
     * Lấy danh sách các ngày trong tuần
     *
     * Mảng index bởi
     * ['dates'][year-week][date]
     *
     * @param int|string $week
     * @param int|string $year
     * @return array
     */
    public function datesOfWeek($week, $year = '')
    {
        $oDate = new DateTime;
        $today = new DateTime;
        $yearweek = $year === '' ? $week : $year . '-W' . $week;

        $oDate->modify($yearweek . '-1');

        $data = array();

        for ($i = 0; $i < 7; $i++) {
            $date = $oDate->formatISO(0);
            $data[$yearweek][$date] = array(
                'date' => $date,
                'diff' => array(
                    'day'   => $today->diffDay($oDate),
                    'month' => $today->diffMonth($oDate)
                )
            );
            $oDate->addDay(1);
        }

        return array('dates' => $data);
    }
}
