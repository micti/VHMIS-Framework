<?php

namespace Vhmis\I18n\Resource;

use Vhmis\Utils\Exception\InvalidArgumentException;
use Vhmis\Utils\Loader\PhpArray as LoaderData;

class Loader
{

    /**
     * Supported locales
     *
     * @var array
     */
    protected static $locales = [
        'vi',
        'vi-VN',
        'en',
        'en-US',
        'ko',
        'ko-KR'
    ];

    /**
     * Supported main data
     *
     * @var array
     */
    protected static $mainData = [
        'ca-buddhist',
        'ca-chinese',
        'ca-coptic',
        'ca-dangi',
        'ca-ethiopic-amete-alem',
        'ca-ethiopic',
        'ca-generic',
        'ca-gregorian',
        'ca-hebrew',
        'ca-indian',
        'ca-islamic-civil',
        'ca-islamic-rgsa',
        'ca-islamic-tbla',
        'ca-islamic-umalqura',
        'ca-islamic',
        'ca-japanese',
        'ca-persian',
        'ca-roc',
        'characters',
        'currencies',
        'dateFields',
        'delimiters',
        'languages',
        'layout',
        'listPatterns',
        'localeDisplayNames',
        'measurementSystemNames',
        'numbers',
        'posix',
        'scripts',
        'territories',
        'timeZoneNames',
        'units',
        'variants',
    ];

    /**
     * Supported supplemental data
     *
     * @var array
     */
    protected static $supplementalData = [
        'plurals'
    ];

    /**
     * Load supplemental data.
     *
     * @param string $field
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    static public function loadSupplementalData($field)
    {
        if (!static::isSupportedSupplementalData($field)) {
            throw new InvalidArgumentException('Data for ' . $field . ' not supported.');
        }

        $file = __DIR__ . D_SPEC . 'Data' . D_SPEC . $field . '.php';

        return LoaderData::load($file);
    }

    /**
     * Load main data.
     *
     * @param string $field
     * @param string $locale
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    static public function loadMainData($field, $locale)
    {
        if (!static::isSupportedMainData($field, $locale)) {
            throw new InvalidArgumentException('Data for ' . $field . ' not supported.');
        }

        $file = static::getLocalePath($field, $locale);

        return LoaderData::load($file);
    }

    /**
     * Check supported main data.
     *
     * @param string $field
     * @param string $locale
     *
     * @return boolean
     */
    static protected function isSupportedMainData($field, $locale)
    {
        if (!static::isSupportedLocale($locale)) {
            return false;
        }

        if (!in_array($field, static::$mainData)) {
            return false;
        }

        return true;
    }

    /**
     * Check supported supplemental data.
     *
     * @param string $field
     *
     * @return boolean
     */
    static protected function isSupportedSupplementalData($field)
    {
        if (!in_array($field, static::$supplementalData)) {
            return false;
        }

        return true;
    }

    /**
     * Check supported locale.
     *
     * @param string $locale
     *
     * @return boolean
     */
    static protected function isSupportedLocale($locale)
    {
        $data = \Locale::parseLocale($locale);

        if (!in_array($locale, static::$locales) && !in_array($data['language'], static::$locales)) {
            return false;
        }

        return true;
    }

    /**
     * Get locale path.
     *
     * @param string $field
     * @param string $locale
     *
     * @return string
     */
    static protected function getLocalePath($field, $locale)
    {
        $data = \Locale::parseLocale($locale);

        $file1 = __DIR__ . D_SPEC . 'Data' . D_SPEC . $locale . D_SPEC . $field . '.php';
        $file2 = __DIR__ . D_SPEC . 'Data' . D_SPEC . $data['language'] . D_SPEC . $field . '.php';

        if (LoaderData::isReadable($file1)) {
            return $file1;
        }

        return $file2;
    }
}
