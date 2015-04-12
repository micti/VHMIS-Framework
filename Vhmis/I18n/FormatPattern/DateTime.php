<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\FormatPattern;

use \IntlDateFormatter;

/**
 * Datetime pattern
 */
class DateTime
{

    /**
     * Lấy format theo PHP date()
     * Dùng chủ yếu kiểm tra dữ liệu nhập vào
     * Do đó ta chỉ convert các dạng ngày tháng năm theo số trong định dạng ngắn
     * type nhận giá trị 2 hoặc 3
     *
     * @param string $locale
     * @param int $type
     * @return string
     */
    static public function dateNativeFormat($type, $locale = '')
    {
        $formatter = new IntlDateFormatter(self::getLocale($locale), $type, IntlDateFormatter::NONE);
        $format = $formatter->getPattern();

        $patterns = array(
            'year' => array(
                'YYYY' => 'o',
                'yyyy' => 'Y',
                'yy' => 'y',
                'y' => 'Y'
            ),
            'day' => array(
                'dd' => 'd',
                'd' => 'j'
            ),
            'month' => array(
                'MM' => 'm',
                'M' => 'n'
            )
        );

        foreach ($patterns as $typePattern) {
            foreach ($typePattern as $pattern => $nativePattern) {
                if (strpos($format, $pattern) !== false) {
                    $format = str_replace($pattern, $nativePattern, $format);
                    break;
                }
            }
        }

        return $format;
    }

    static public function dateFormat($type, $locale = '')
    {
        $formatter = new IntlDateFormatter(self::getLocale($locale), $type, IntlDateFormatter::NONE);
        $format = $formatter->getPattern();

        return $format;
    }

    static public function timeFormat($type, $locale = '')
    {
        $formatter = new IntlDateFormatter(self::getLocale($locale), IntlDateFormatter::NONE, $type);
        $format = $formatter->getPattern();

        return $format;
    }

    static public function dateTimeFormat($dateType, $timeType, $locale = '')
    {
        $formatter = new IntlDateFormatter(self::getLocale($locale), $dateType, $timeType);
        $format = $formatter->getPattern();

        return $format;
    }

    /**
     * Get locale.
     * 
     * @param string $locale
     * 
     * @return string
     */
    static public function getLocale($locale)
    {
        if ($locale === '') {
            return \Locale::getDefault();
        }

        return $locale;
    }
}
