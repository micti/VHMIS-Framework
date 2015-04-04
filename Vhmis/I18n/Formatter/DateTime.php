<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Formatter;

use Vhmis\I18n\Resource\Resource;
use IntlDateFormatter;

/**
 * Format datetime in string
 */
class DateTime
{
    /**
     * IntlDateFormatter objects.
     *
     * @var IntlDateFormatter[]
     */
    protected $intlDateFormatters = [];

    /**
     * Pattern id list.
     *
     * @var array
     */
    protected $patternIds = array(
        'd',
        'Ed',
        'Gy',
        'GyMMM',
        'GyMMMd',
        'GyMMMEd',
        'h',
        'H',
        'hm',
        'Hm',
        'hms',
        'Hms',
        'M',
        'Md',
        'MEd',
        'MMdd',
        'MMM',
        'MMMd',
        'MMMEd',
        'MMMMd',
        'MMMMEd',
        'mmss',
        'ms',
        'y',
        'yM',
        'yMd',
        'yMEd',
        'yMM',
        'yMMM',
        'yMMMd',
        'yMMMEd',
        'yMMMM',
        'yQQQ',
        'yQQQQ'
    );

    /**
     * Format datetime string by style.
     *
     * @param \Vhmis\I18n\DateTime\DateTime $datetime
     * @param int|null $dateStyle
     * @param int|null $timeStyle
     * @param string $locale
     *
     * @return string
     */
    public function style($datetime, $dateStyle = 3, $timeStyle = 3, $locale = '')
    {
        if ($locale === '') {
            $locale = \Locale::getDefault();
        }

        return $datetime->format([$dateStyle, $timeStyle], $locale);
    }

    /**
     * Format datetime string by pattern.
     *
     * @param \Vhmis\I18n\DateTime\DateTime $datetime
     * @param string $pattern
     * @param string $locale
     *
     * @return string
     */
    public function pattern($datetime, $pattern, $locale)
    {
        if(!in_array($pattern, $this->patternIds)) {
            return $datetime->format([3, 3], $locale);
        }

        $format = Resource::getDateFormat($pattern, $datetime->getType(), $locale);

        if ($format === '') {
            return $datetime->format([3, 3], $locale);
        }

        // Error with utf8
        // return $datetime->format($format, $locale);

        $formatter = $this->getDateTimeFormatter($locale, $datetime->getType());
        $formatter->setTimeZone($datetime->getTimeZone());
        $formatter->setPattern($format);

        return $formatter->format($datetime->getTimestamp());
    }

    /**
     * Get IntlDateFormatter by locale and calendar.
     *
     * @param string $locale
     * @param string $calendar
     *
     * @return IntlDateFormatter
     */
    protected function getDateTimeFormatter($locale, $calendar)
    {
        if ($locale === '') {
            $locale = \Locale::getDefault();
        }

        $calendarType = IntlDateFormatter::GREGORIAN;

        if ($calendar !== 'gregorian') {
            $locale .= '@calendar=' . $calendar;
            $calendarType = IntlDateFormatter::TRADITIONAL;
        }

        if (!isset($this->intlDateFormatter[$locale])) {
            $this->intlDateFormatter[$locale] = new IntlDateFormatter($locale, 0, 0);
            $this->intlDateFormatter[$locale]->setCalendar($calendarType);
        }

        return $this->intlDateFormatter[$locale];
    }
}
