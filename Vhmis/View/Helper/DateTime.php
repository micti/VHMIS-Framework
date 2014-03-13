<?php

namespace Vhmis\View\Helper;

class DateTime extends HelperAbstract
{
    /**
     * Đối tượng DateTime Output
     *
     * @var \Vhmis\I18n\Output\DateTime
     */
    protected $dt;

    protected $locale;

    public function __construct()
    {
        $this->dt = new \Vhmis\I18n\Output\DateTime();
        $this->locale = \Locale::getDefault();
    }

    public function format($date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        if($pattern === '') {
            return $this->dt->dateTime($date, $dateStyle, $timeStyle);
        }

        return $this->dt->customPattern($date, $pattern);
    }

    public function formatInput($date)
    {
        return $this->dt->customPattern($date, \Vhmis\I18n\FormatPattern\DateTime::dateFormat($this->locale, 3));
    }

    public function relative($relative, $date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        return $this->dt->relative($relative, $date, $dateStyle, $timeStyle, $pattern);
    }

    public function appendPattern($value, $pattern, $item)
    {
        return $this->dt->appendPattern($value, $pattern, $item);
    }

    public function yearMonth($date)
    {
        return $this->dt->yearMonth($date);
    }

    public function yearWeek($date)
    {
        return $this->dt->yearWeek($date);
    }

    public function linkYearMonth($date)
    {
        return date('Y-m', strtotime($date));
    }

    public function linkYearWeek($date)
    {
        return date('o-\wW', strtotime($date));
    }

    public function interval($date1, $date2, $pattern) {
        return $this->dt->range($date1, $date2, $pattern);
    }

    public function ago($date1, $date2 = '') {
        return $this->dt->ago($date1, $date2);
    }

    /**
     * Tên tháng
     *
     * @param string $month
     * @param string $format
     * @param string $type
     * @return string
     */
    public function monthName($month, $type = 'stand-alone', $format = 'wide') {
        return $this->dt->calendarFieldName('months', $type, $format);
    }

    /**
     * Tên ngày trong tuần
     *
     * @param string $day
     * @param string $type
     * @param string $format
     * @return string
     */
    public function dayName($day, $type = 'stand-alone', $format = 'wide') {
        return $this->dt->calendarFieldName($day, 'days', $type, $format);
    }

    public function fieldName($field, $format = 'displayName') {
        return $this->dt->dateFieldName($field, $format);
    }

    public function unit($number, $filed) {
        return $this->dt->unit($number, $filed);
    }
}
