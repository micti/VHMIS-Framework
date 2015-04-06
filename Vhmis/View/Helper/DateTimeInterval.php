<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\Formatter\DateTimeInterval as dtIntervalFormatter;
use Vhmis\I18n\DateTime\DateTime;

class DateTimeInterval extends AbstractDateTime
{

    /**
     * DateTimeInterval formatter object.
     *
     * @var dtIntervalFormatter
     */
    protected $dtIntervalFormatter;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->dtIntervalFormatter = new dtIntervalFormatter;
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
    public function __invoke($date1, $date2, $type, $timezone = null, $calendar = '', $locale = '')
    {
        if (!($date1 instanceof DateTime)) {
            $date1 = $this->getDate($date1, $timezone, $calendar);
        }

        if (!($date2 instanceof DateTime)) {
            $date2 = $this->getDate($date2, $timezone, $calendar);
        }

        return $this->dtIntervalFormatter->interval($date1, $date2, $type, $locale);
    }
}
