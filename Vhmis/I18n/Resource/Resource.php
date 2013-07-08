<?php

namespace Vhmis\I18n\Resource;

class Resource
{
    /**
     * Dữ liệu
     *
     * @var array
     */
    protected static $data = array();

    public static function locale($locale)
    {
        return str_replace('_', '-', $locale);
    }

    /**
     * Load file dữ liệu vào data
     *
     * @param string $locale
     * @param string $field
     */
    public static function loadResource($locale, $field)
    {
        $locale = static::locale($locale);

        $lang = explode('-', $locale);
        $lang = $lang[0];

        if (is_readable(__DIR__ . D_SPEC . $locale . D_SPEC . $field . '.php')) {
            static::$data[$locale . '-' . $field] = include $locale . D_SPEC . $field . '.php';
            return;
        }

        if (is_readable(__DIR__ . D_SPEC . $lang . D_SPEC . $field . '.php')) {
            static::$data[$locale . '-' . $field] = include $lang . D_SPEC . $field . '.php';
            return;
        }
    }

    public static function getDateTimePattern($datePattern, $timePattern, $locale = '', $calendar = 'gregorian')
    {
        $locale = static::locale($locale);

        static::loadResource($locale, 'ca-' . $calendar);

        $index = $locale . '-ca-' . $calendar;

        // Pattern
        if (isset(static::$data[$index]['dates']['calendars'][$calendar]['dateTimeFormats']['availableFormats'][$datePattern])) {
            $datePattern = static::$data[$index]['dates']['calendars'][$calendar]['dateTimeFormats']['availableFormats'][$datePattern];
        } else {
            $datePattern = '';
        }

        if (isset(static::$data[$index]['dates']['calendars'][$calendar]['dateTimeFormats']['availableFormats'][$timePattern])) {
            $timePattern = static::$data[$index]['dates']['calendars'][$calendar]['dateTimeFormats']['availableFormats'][$timePattern];
        } else {
            $timePattern = '';
        }

        if ($timePattern != '' && $datePattern != '') {
            $pattern = static::$data[$index]['dates']['calendars'][$calendar]['dateTimeFormats']['short'];
            $pattern = str_replace(array('{1}', '{0}'), array($datePattern, $timePattern), $pattern);
        } else {
            $pattern = $timePattern === '' ? $datePattern : $timePattern;
        }

        return $pattern;
    }

    public static function getDateField($field, $locale = '') {
        $locale = static::locale($locale);

        static::loadResource($locale, 'dateFields');

        $index = $locale . '-dateFields';

        if (isset(static::$data[$index]['dates']['fields'][$field])) {
            return static::$data[$index]['dates']['fields'][$field];
        }

        return array();
    }
}
