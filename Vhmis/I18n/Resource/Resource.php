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

        return static::$i18nData[$locale][$field];
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
