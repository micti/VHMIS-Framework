<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\I18n\DateTime\DateTime;

class Convert extends AbstractHelper
{
    /**
     * Cache objects for convert ...
     *
     * @var DateTime[]
     */
    protected static $cachedDateTimes = array();

    /**
     * Not support __invoke
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return null
     */
    public function __invoke($name, $arguments)
    {
        return null;
    }

    /**
     * Convert date to other calendar
     *
     * @param string $calendar
     *
     * @return array
     */
    public function to($calendar)
    {
        $result = array();

        if (!isset(DateTime::$calendars[$calendar])) {
            return $result;
        }

        $calendarObject = static::getCachedDateTime($calendar);
        $calendarObject->setTimeZone($this->date->getTimeZone());
        $calendarObject->setTimestamp($this->date->getTimestamp());

        $result['origin'] = $calendarObject->getDate();
        $result['extendedyear'] = $calendarObject->getDateWithExtendedYear();
        $result['relatedyear'] = $calendarObject->getDateWithRelatedYear();

        return $result;
    }

    /**
     * Get cached calendar for convert
     *
     * @param string $calendar
     *
     * @return DateTime
     */
    protected static function getCachedDateTime($calendar)
    {
        if (isset(static::$cachedDateTimes[$calendar])) {
            return static::$cachedDateTimes[$calendar];
        }

        static::$cachedDateTimes[$calendar] = new DateTime(null, $calendar, \Locale::getDefault());

        return static::$cachedDateTimes[$calendar];
    }
}
