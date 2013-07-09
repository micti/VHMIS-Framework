<?php

namespace Vhmis\I18n\Resource;

class Resource
{
    /**
     * Mảng chứa dữ liệu
     *
     * @var array
     */
    protected static $i18nData = array();

    /**
     * Locale mặc định
     *
     * @var array
     */
    protected static $locale = 'vi';

    /**
     * Danh mục các locale support
     *
     * @var type
     */
    protected static $locales = array(
        'vi',
        'en',
        'ko'
    );

    /**
     * Danh mục các dữ liệu chính support
     *
     * @var type
     */
    protected static $main = array(
        'gregorian' => 'ca-gregorian',
        'datefields' => 'dateFields',
        'listPattern',
        'number',
        'units'
    );

    /**
     * Chỉnh lại tên locale đúng với tên trong dữ liệu CLDR
     *
     * @param string $locale
     * @return string
     */
    protected static function fixLocaleName($locale)
    {
        return str_replace('_', '-', $locale);
    }

    /**
     * Load dữ liệu
     *
     * @param string $field
     * @param string $locale
     * @throws \Exception Ngoại lệ nếu dữ liệu locale không được support
     */
    protected static function loadMain($field, $locale = '')
    {
        $locale = static::fixLocaleName($locale);
        $locale = $locale == '' ? self::$locale : str_replace('_', '-', $locale);

        if(isset(static::$i18nData[$field])) {
            throw new \Exception($field . ' I18n Data Not Supported.');
        }

        list($lang, $ter) = explode('-', $locale, 2);

        $data = array();

        /** pls check **/
        if (is_readable(__DIR__ . D_SPEC . $locale . D_SPEC . static::$main[$field] . '.php')) {
            $data = include $locale . D_SPEC . static::$main[$field] . '.php';
        }

        /** pls check **/
        if (is_readable(__DIR__ . D_SPEC . $lang . D_SPEC . static::$main[$field] . '.php')) {
            $data = $data + include $lang . D_SPEC . static::$main[$field] . '.php';
        }

        if($field === 'gregorian') {
            $data = $data['dates']['calendar']['gregorian'];
        } else if($field === 'datefields') {
            $data = $data['dates']['fields'];
        }

        static::$i18nData[$locale][$field] = $data;
        unset($data);
    }

    /**
     * Lấy pattern của ngày giờ dựa theo format Id của nó
     *
     * Nếu chỉ muốn lấy 1 trong 2 thì truyền vào giá trị rỗng cho một tham số bất kỳ
     *
     * @param string $pDate format Id của ngày
     * @param string $pTime format Id của giờ
     * @param string $locale
     * @param string $calendar
     * @return string
     */
    public static function datePattern($pDate, $pTime, $locale = '', $calendar = 'gregorian')
    {
        if($calendar !== 'gregorian') {
            return '';
        }

        $locale = static::fixLocaleName($locale);
        static::loadMain($calendar, $locale);

        if ($pDate !== '' && isset(static::$i18nData[$locale][$calendar]['dateTimeFormats']['availableFormats'][$pDate])) {
            $pDate = static::$i18nData[$locale][$calendar]['dateTimeFormats']['availableFormats'][$pDate];
        }

        if ($pTime !== '' && isset(static::$i18nData[$locale][$calendar]['dateTimeFormats']['availableFormats'][$pTime])) {
            $pTime = static::$i18nData[$locale][$calendar]['dateTimeFormats']['availableFormats'][$pTime];
        }

        if ($pDate != '' && $pTime != '') {
            $pattern = static::$i18nData[$index][$calendar]['dateTimeFormats']['short'];
            $pattern = str_replace(array('{1}', '{0}'), array($pDate, $pTime), $pattern);
        } else {
            $pattern = $pDate === '' ? $pTime : $pDate;
        }

        return $pattern;
    }

    /**
     * Lấy dữ liệu trong dateFields
     *
     * @param string $field
     * @param string $locale
     * @return array
     */
    public static function dateField($field, $locale = '') {
        $locale = static::fixLocaleName($locale);
        static::loadMain('datefields', $locale);

        if (isset(static::$i18nData[$locale]['datefields'][$field])) {
            return static::$i18nData[$locale]['datefields'][$field];
        }

        return array(
            'displayName' => '',
            '-1' => '',
            '0' => '',
            '1' => ''
        );
    }
}
