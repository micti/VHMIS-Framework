<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_I18n
 * @since Vhmis v2.0
 */

namespace Vhmis\I18n\Output;

use \IntlDateFormatter;
use \Vhmis\I18n\Resource\Resource as I18nResource;

/**
 * Xuất ngày giờ theo các định dạng
 *
 * @category Vhmis
 * @package Vhmis_I18n
 * @subpackage Output
 */
class DateTime
{
    /**
     * Locale mặc định
     *
     * @var string
     */
    protected $_locale;

    /**
     * Các đối tượng IntlDateFormatter, ứng với mỗi cặp locale và format style
     *
     * @var array
     */
    protected $_formatters = array();

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        // Locale mặc định
        $this->_locale = 'vi_VN';
    }

    /**
     * Thiết lập Locale
     *
     * @param string $locale Locale
     */
    public function setLocale($locale = null)
    {
        if (null !== $locale)
            $this->_locale = $locale;
    }

    /**
     * Xuất ngày theo các định dạng theo kiểu định nghĩa sẵn trong PHP
     *
     * @param type $value
     * @param type $style Kiểu
     * @return string
     */
    public function date($value, $style)
    {
        $timeStyle = IntlDateFormatter::NONE;

        $formatter = md5($this->_locale . $style . $timeStyle);

        if (!isset($this->_formatters[$formatter])) {
            $this->_formatters[$formatter] = new IntlDateFormatter($this->_locale, $style, $timeStyle);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        $string = $this->_formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function dateTime($value, $dateStyle, $timeStyle)
    {
        $formatter = md5($this->_locale . $dateStyle . $timeStyle);

        if (!isset($this->_formatters[$formatter])) {
            $this->_formatters[$formatter] = new IntlDateFormatter($this->_locale, $dateStyle, $timeStyle);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        //$this->_formatters[$formatter]->setPattern(null);
        $string = $this->_formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function customPattern($value, $pattern)
    {
        $formatter = md5($this->_locale . 'custom');

        if (!isset($this->_formatters[$formatter])) {
            $this->_formatters[$formatter] = new IntlDateFormatter($this->_locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        $this->_formatters[$formatter]->setPattern($pattern);

        $string = $this->_formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function relative($relative, $date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        if(isset($relative['d'])) {
            $day = I18nResource::getDateField('day', $this->_locale);
            return $day[$relative['d']];
        }

        if(isset($relative['w'])) {
            $day = I18nResource::getDateField('week', $this->_locale);
            return $this->customPattern($date, 'EEEE') . ' ' . $day[$relative['w']];
        }

        if(isset($relative['m'])) {
            $day = I18nResource::getDateField('month', $this->_locale);
            return $this->customPattern($date, I18nResource::getDateTimePattern('d', '', $this->_locale)) . ' ' . $day[$relative['m']];
        }

        if(isset($relative['y'])) {
            $day = I18nResource::getDateField('year', $this->_locale);
            return $this->customPattern($date, I18nResource::getDateTimePattern('Md', '', $this->_locale)) . ' ' . $day[$relative['y']];
        }

        if($pattern == '') {
            return $this->dateTime($date, $dateStyle, $timeStyle);
        } else {
            return $this->customPattern($date, $pattern);
        }
    }
}
