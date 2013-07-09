<?php

namespace Vhmis\View\Helper;

class DateTime extends HelperAbstract
{
    /**
     * Đối tượng Escaper
     *
     * @var \Vhmis\I18n\Output\DateTime
     */
    protected $dt;

    public function __construct()
    {
        $this->dt = new \Vhmis\I18n\Output\DateTime();
    }

    public function format($date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        if($pattern === '') {
            return $this->dt->dateTime($date, $dateStyle, $timeStyle);
        }

        return $this->dt->customPattern($date, $pattern);
    }

    public function relative($relative, $date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        return $this->dt->relative($relative, $date, $dateStyle, $timeStyle, $pattern);
    }

    public function linkYearMonth($date)
    {
        return date('Y-m', strtotime($date));
    }

    public function linkYearWeek($date)
    {
        return date('o-\wW', strtotime($date));
    }
}
