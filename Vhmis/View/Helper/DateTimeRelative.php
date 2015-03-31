<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\Formatter\DateTimeRelative as dtRelativeFormatter;
use Vhmis\I18n\DateTime\DateTime;

class DateTimeRelative extends HelperAbstract
{

    /**
     *
     * @var dtRelativeFormatter
     */
    protected $dtRelativeFormatter;

    public function __construct()
    {
        $this->dtRelativeFormatter = new dtRelativeFormatter;
    }

    public function __invoke($date, $rootDate = null, $timezone = null, $calendar = '', $locale = '')
    {
        if (!($date instanceof DateTime)) {
            $date = $this->getDate($date, $timezone, $calendar);
        }

        if (!($rootDate instanceof DateTime)) {
            $rootDate = $this->getDate($rootDate, $timezone, $calendar);
        }

        return $this->dtRelativeFormatter->relative($date, $rootDate, $locale);
    }

    protected function getDate($date, $timezone, $calendar)
    {
        $datestring = $date;
        if (!is_string($date)) {
            $datestring = '';
        }

        $date = new DateTime($timezone, $calendar);
        return $date->modify($datestring);
    }
}
