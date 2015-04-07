<?php

namespace Vhmis\View\Helper;

class DateTime1 extends HelperAbstract
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


    /**
     * Xuất định dạng ngày tháng cho ô input
     *
     * @param mixed $date Thời gian
     * @param string $type Loại xuất ra, 'date' => Chỉ ngày, 'time' => Chỉ giờ
     * @return string
     */
    public function formatInput($date, $type = 'date')
    {
        if ($type === 'date') {
            return $this->dt->customPattern($date, \Vhmis\I18n\FormatPattern\DateTime::dateFormat($this->locale, 3));
        } elseif ($type === 'time') {
            return $this->dt->customPattern($date, \Vhmis\I18n\FormatPattern\DateTime::timeFormat($this->locale, 3));
        } else {
            return $this->dt->customPattern($date, \Vhmis\I18n\FormatPattern\DateTime::dateTimeFormat($this->locale, 3, 3));
        }
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

    public function unit($number, $filed)
    {
        return $this->dt->unit($number, $filed);
    }
}
