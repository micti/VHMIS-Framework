<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\Formatter\DateTimeRelative as dtRelativeFormatter;
use Vhmis\I18n\DateTime\DateTime;

class DateTimeRelative extends AbstractDateTime
{

    /**
     * DateTimeRelative formatter object.
     *
     * @var dtRelativeFormatter
     */
    protected $dtRelativeFormatter;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->dtRelativeFormatter = new dtRelativeFormatter;
    }

    /**
     * Get relative string.
     *
     * @param DateTime|mixed $date
     * @param DateTime|mixed $rootDate
     * @param string|mixed $timezone
     * @param string $calendar
     * @param string $locale
     *
     * @return string
     */
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
}
