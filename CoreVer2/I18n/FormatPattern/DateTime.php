<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link       http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright  Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @package    Vhmis_I18n
 * @since      Vhmis v2.0
 */
namespace Vhmis\I18n\FormatPattern;
use IntlDateFormatter;

/**
 * Lấy các format của thời gian dựa theo Locale
 *
 * @category Vhmis
 * @package Vhmis_I18n
 * @subpackage FormatPattern
 */
class DateTime
{

    /**
     * Lấy định dạng của ngày
     *
     * @param string $locale            
     * @param int $type            
     * @return string
     */
    static public function date($locale, $type)
    {
        $format = new IntlDateFormatter($locale, $type, IntlDateFormatter::NONE);
        return $format->getPattern();
    }

    /**
     * Lấy định dạng của giờ
     *
     * @param string $locale            
     * @param int $type            
     * @return string
     */
    static public function time($locale, $type)
    {
        $format = new IntlDateFormatter($locale, IntlDateFormatter::NONE, $type);
        return $format->getPattern();
    }

    /**
     * Lấy định dạng của ngày và giờ
     *
     * @param string $locale            
     * @param int $type            
     * @return string
     */
    static public function dateTime($locale, $type)
    {
        $format = new IntlDateFormatter($locale, $type, $type);
        return $format->getPattern();
    }

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
    static public function dateNativeFormat($locale, $type)
    {
        $formatter = new IntlDateFormatter($locale, $type, IntlDateFormatter::NONE);
        $format = $formatter->getPattern();
        
        $patterns = array('year' => array('YYYY' => 'o', 'yyyy' => 'Y', 'yy' => 'y'), 'day' => array('dd' => 'd', 'd' => 'j'), 'month' => array('MM' => 'm', 'M' => 'n'));
        
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
}
