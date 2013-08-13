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
     * @var string
     */
    protected static $locale;

    /**
     * Danh mục các locale support
     *
     * @var array
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
        'gregorian'  => 'ca-gregorian',
        'datefields' => 'dateFields',
        'list'       => 'listPatterns',
        'number',
        'units'      => 'units'
    );

    /**
     * Chỉnh lại tên locale đúng với tên trong dữ liệu CLDR
     *
     * @param string $locale
     * @return string
     */
    protected static function fixLocaleName($locale)
    {
        $locale = str_replace('_', '-', $locale);

        if($locale == '') {
            $locale = locale_get_default();
        }

        return $locale;

    }

    protected static function loadSupplemental($supplemental)
    {
        if (isset(static::$i18nData[$supplemental])) {
            return;
        }

        if (is_readable(__DIR__ . D_SPEC . $supplemental . '.php')) {
            $data = include $supplemental . '.php';
        }

        static::$i18nData[$supplemental] = $data;
        unset($data);
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

        if (isset(static::$i18nData[$locale][$field])) {
            return;
            //throw new \Exception($field . ' I18n Data Not Supported.');
        }

        list($lang, $ter) = explode('-', $locale, 2);

        $data = array();

        /** pls check * */
        if (is_readable(__DIR__ . D_SPEC . $locale . D_SPEC . static::$main[$field] . '.php')) {
            $data = include $locale . D_SPEC . static::$main[$field] . '.php';
        }

        /** pls check * */
        if (is_readable(__DIR__ . D_SPEC . $lang . D_SPEC . static::$main[$field] . '.php')) {
            $data = $data + include $lang . D_SPEC . static::$main[$field] . '.php';
        }

        if ($field === 'gregorian') {
            $data = $data['dates']['calendars']['gregorian'];
        } else if ($field === 'datefields') {
            $data = $data['dates']['fields'];
        } else if ($field === 'list') {
            $data = $data['listPatterns']['listPattern'];
        } else if ($field === 'units') {
            $data = $data['units'];
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
        if ($calendar !== 'gregorian') {
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
    public static function dateField($field, $locale = '')
    {
        $locale = static::fixLocaleName($locale);
        static::loadMain('datefields', $locale);

        if (isset(static::$i18nData[$locale]['datefields'][$field])) {
            return static::$i18nData[$locale]['datefields'][$field];
        }

        return array(
            'displayName' => '',
            '-1'          => '',
            '0'           => '',
            '1'           => ''
        );
    }

    public static function dateIntervalPattern($field, $diffrenceField, $locale = '', $calendar = 'gregorian')
    {
        $falseReturn = array(
            'pattern'      => '{0} - {1}',
            'patternbegin' => 'YYYY-MM-dd HH:mm:ss',
            'patternend'   => 'YYYY-MM-dd HH:mm:ss'
        );

        $punctuationMark = '/-|~|‐|–|—/';
        if ($calendar !== 'gregorian') {
            return $falseReturn;
        }

        $locale = static::fixLocaleName($locale);
        static::loadMain($calendar, $locale);

        $formatFallback = static::$i18nData[$locale][$calendar]['dateTimeFormats']['intervalFormats']['intervalFormatFallback'];

        if (!isset(static::$i18nData[$locale][$calendar]['dateTimeFormats']['intervalFormats'][$field][$diffrenceField])) {
            // TODO: check again format Id
            return $falseReturn;
        }

        $pattern = static::$i18nData[$locale][$calendar]['dateTimeFormats']['intervalFormats'][$field][$diffrenceField];

        $formatpart = preg_split($punctuationMark, $formatFallback);

        if (count($formatpart) !== 2) {
            return $falseReturn;
        }

        // The begin date in the first flag
        $firstIsFirst = true;
        if (trim($formatpart[0]) === '{1}') {
            $firstIsFirst = false;
        }

        $patternpart = preg_split($punctuationMark, $pattern);

        $patternpart[0] = trim($patternpart[0]);
        $patternpart[1] = trim($patternpart[1]);

        if (strlen($patternpart[0]) >= strlen($patternpart[1])) {
            $pattern = str_replace($patternpart[0], '', $pattern);
            $pattern = str_replace($patternpart[1], '', $pattern);
        } else {
            $pattern = str_replace($patternpart[1], '', $pattern);
            $pattern = str_replace($patternpart[0], '', $pattern);
        }

        // Replace begin
        if ($firstIsFirst) {
            $pattern = '{0}' . $pattern . '{1}';
        } else {
            $pattern = '{1}' . $pattern . '{0}';
        }

        return array(
            'pattern'      => $pattern,
            'patternbegin' => $firstIsFirst ? $patternpart[0] : $patternpart[1],
            'patternend'   => $firstIsFirst ? $patternpart[1] : $patternpart[0]
        );
    }

    public static function listPattern($locale)
    {
        $locale = static::fixLocaleName($locale);
        static::loadMain('list', $locale);

        return static::$i18nData[$locale]['list'];
    }

    public static function units($field, $locale)
    {
        $locale = static::fixLocaleName($locale);
        static::loadMain('units', $locale);

        return static::$i18nData[$locale]['units'][$field];
    }

    /**
     * Lấy luật về số nhiều
     *
     * @param string $locale
     * @return array
     */
    public static function pluralsRule($locale)
    {
        $locale = static::fixLocaleName($locale);
        static::loadSupplemental('plurals');

        $lang = explode('-', $locale);

        return static::$i18nData['plurals'][$lang[0]];
    }

    /**
     * Lấy trường thông tin của 1 calendar
     *
     * @param string $field
     * @param string $locale
     * @param strung $calendar
     * @return array
     */
    public static function calendarField($field, $locale = '', $calendar = 'gregorian')
    {
        if ($calendar !== 'gregorian') {
            return array();
        }

        $locale = static::fixLocaleName($locale);
        static::loadMain($calendar, $locale);

        return static::$i18nData[$locale][$calendar][$field];
    }
}
