<?php

namespace Vhmis\I18n\Resource;

class Resource
{

    /**
     * Data storage.
     *
     * @var array
     */
    protected static $i18nData = [];

    /**
     * Get cardinal plural rule.
     *
     * @param string $locale
     *
     * @return array
     */
    public static function getCardinalPluralRule($locale = '')
    {
        $lang = explode('-', static::getLocale($locale));
        return static::getSupplementalData('plurals')[$lang[0]];
    }

    public static function getCalendarField($field, $value, $width, $context = 'stand-alone', $calendar = 'gregorian', $locale = '')
    {
        $data = static::getMainData('ca-' . $calendar, $locale)['dates']['calendars'][$calendar];

        if (!isset($data[$field][$context][$width][$value])) {
            return '';
        }

        return $data[$field][$context][$width][$value];
    }

    public static function getDateField($type, $locale = '')
    {
        $locale = static::getLocale($locale);
        return static::getMainData('dateFields', $locale)['dates']['fields'][$type];
    }

    public static function getDateFormat($type, $calendar = 'gregorian', $locale = '')
    {
        $data = static::getMainData('ca-' . $calendar, $locale)['dates']['calendars'][$calendar]['dateTimeFormats']['availableFormats'];

        if (!isset($data[$type])) {
            return '';
        }

        return $data[$type];
    }

    /**
     *
     * @param string $type
     * @param string $calendar
     * @param string $locale
     *
     * @return array|string|null
     */
    public static function getDateIntervalFormat($type, $calendar = 'gregorian', $locale = '')
    {
        $data = static::getMainData('ca-' . $calendar, $locale)['dates']['calendars'][$calendar]['dateTimeFormats']['intervalFormats'];

        if (!isset($data[$type])) {
            return null;
        }

        return $data[$type];
    }

    /**
     * Get main data.
     *
     * @param string $field
     * @param string $locale
     *
     * @return array
     */
    protected static function getMainData($field, $locale = '')
    {
        $fixlocale = static::getLocale($locale);
        if (!isset(static::$i18nData[$fixlocale][$field])) {
            static::$i18nData[$fixlocale][$field] = Loader::loadMainData($field, $fixlocale);
        }

        return static::$i18nData[$fixlocale][$field];
    }

    /**
     * Get supplemental data.
     *
     * @param string $field
     *
     * @return array
     */
    protected static function getSupplementalData($field)
    {
        if (!isset(static::$i18nData[$field])) {
            static::$i18nData[$field] = Loader::loadSupplementalData($field);
        }

        return static::$i18nData[$field];
    }

    /**
     * Get locale.
     *
     * @param string $locale
     *
     * @return string
     */
    protected static function getLocale($locale)
    {
        $fixlocale = $locale === '' ? \Locale::getDefault() : $locale;

        return str_replace('_', '-', $fixlocale);
    }
}
