<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\DateTime\DateTime;

abstract class AbstractDateTime extends HelperAbstract
{

    /**
     * Get Inlt DateTime object.
     *
     * @param string|mixed $date
     * @param string|mixed $timezone
     * @param string $calendar
     *
     * @return DateTime
     */
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
