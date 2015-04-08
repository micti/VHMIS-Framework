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

/**
 * Interval string of 2 datetimes.
 *
 * Example: 12 - 18 April 2014
 */
class DateTimeInterval
{

    /**
     * Relative string of 2 datetimes.
     *
     * @param \Vhmis\I18n\DateTime\DateTime $date1
     * @param \Vhmis\I18n\DateTime\DateTime $date2
     * @param string $type
     * @param string $locale
     *
     * @return string
     */
    public function interval($date1, $date2, $type, $locale = '')
    {
        if ($date1->getMilliTimestamp() > $date2->getMilliTimestamp()) {
            return $this->interval($date2, $date1, $type, $locale);
        }

        $diff = $date1->diffCheck($date2);
        $fields = [
            'era' => 'G',
            'year' => 'y',
            'month' => 'M',
            'day' => 'd',
            'am_pm' => 'a',
            'hour_am_pm' => 'h',
            'minute' => 'm'
        ];

        // If type contains H (24 hours) instead of h (12 hours)
        if (strpos($type, 'H') !== false) {
            $fields = [
                'era' => 'G',
                'year' => 'y',
                'month' => 'M',
                'day' => 'd',
                'hour' => 'H',
                'minute' => 'm'
            ];
        }

        $greatesDiffField = '';

        foreach ($fields as $key => $field) {
            if ($diff[$key]) {
                $greatesDiffField = $field;
                break;
            }
        }

        if ($greatesDiffField === '') {
            return $this->formatOne($date1, $type, $locale);
        }

        if ($type === 'intervalFormatFallback') {
            return $this->formatFallback($date1, $date2, $locale);
        }

        $patterns = Resource::getDateIntervalFormat($type, $date1->getType(), $locale);

        if ($patterns === null) {
            return $this->formatFallback($date1, $date2, $locale);
        }

        if (!isset($patterns[$greatesDiffField])) {
            return $this->formatFallback($date1, $date2, $locale);
        }

        return $this->format($date1, $date2, $patterns[$greatesDiffField], $locale);
    }

    /**
     * Format by interval pattern.
     *
     * @param \Vhmis\I18n\DateTime\DateTime $date1
     * @param \Vhmis\I18n\DateTime\DateTime $date2
     * @param string $pattern
     * @param string $locale
     *
     * @return string
     */
    protected function format($date1, $date2, $pattern, $locale)
    {
        $fallbackPattern = Resource::getDateIntervalFormat('intervalFormatFallback', $date1->getType(), $locale);
        $isEarliestFirst = $this->isEarliestFirst($pattern, $fallbackPattern);
        $pattern = str_replace(['earliestFirst:', 'latestFirst:'], '', $pattern);

        // This is not best practice to explode interval pattern into 2 parts
        // See http://www.unicode.org/reports/tr35/tr35-dates.html#intervalFormats for more info.
        $part = [];
        preg_match('/(.*)(-|~|‐|–|—)(.*)/', $pattern, $part);

        if ($isEarliestFirst) {
            $first = $date1->format($part[1], $locale);
            $second = $date2->format($part[3], $locale);
        } else {
            $first = $date2->format($part[3], $locale);
            $second = $date1->format($part[1], $locale);
        }

        return $first . $part[2] . $second;
    }

    /**
     * Fallback format when interval pattern doesn't exist.
     *
     * @param \Vhmis\I18n\DateTime\DateTime $date1
     * @param \Vhmis\I18n\DateTime\DateTime $date2
     * @param string $locale
     *
     * @return string
     */
    protected function formatFallback($date1, $date2, $locale)
    {
        $fallbackPattern = Resource::getDateIntervalFormat('intervalFormatFallback', $date1->getType(), $locale);
        $isEarliestFirst = $this->isEarliestFirst('', $fallbackPattern);
        $pattern = [3, 3];

        $first = $date1->format($pattern, $locale);
        $second = $date2->format($pattern, $locale);
        $replace = [$first, $second];
        if (!$isEarliestFirst) {
            $replace = [$second, $first];
        }

        return str_replace(['{0}', '{1}'], $replace, $fallbackPattern);
    }

    /**
     * Format only one date if 2 dates are same (from era to minute field).
     *
     * @param \Vhmis\I18n\DateTime\DateTime $date
     * @param string $type
     * @param string $locale
     *
     * @return string
     */
    protected function formatOne($date, $type, $locale)
    {
        $pattern = Resource::getDateFormat($type, $date->getType(), $locale);

        if ($pattern === '') {
            $pattern = [3, 3];
        }

        return $date->format($pattern, $locale);
    }

    /**
     * Check if start time is first in interval pattern.
     *
     * @param string $pattern
     * @param string $fallbackPattern
     *
     * @return boolean
     */
    protected function isEarliestFirst($pattern, $fallbackPattern)
    {
        if (strpos($pattern, 'earliestFirst:') === true) {
            return true;
        }

        if (strpos($pattern, 'latestFirst:') === true) {
            return false;
        }

        if (strpos($fallbackPattern, '{1}') < strpos($fallbackPattern, '{0}')) {
            return false;
        }

        return true;
    }

}
