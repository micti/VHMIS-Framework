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
    protected $locale;

    /**
     * Các đối tượng IntlDateFormatter, ứng với mỗi cặp locale và format style
     *
     * @var array
     */
    protected $formatters = array();

    /**
     * Danh sách pattern id dùng trong định dạng ngày tháng
     *
     * @var array
     */
    protected $dateTimePatternId = array(
        'd',
        'Ed',
        'Gy',
        'GyMMM',
        'GyMMMd',
        'GyMMMEd',
        'h',
        'H',
        'hm',
        'Hm',
        'hms',
        'Hms',
        'M',
        'Md',
        'MEd',
        'MMdd',
        'MMM',
        'MMMd',
        'MMMEd',
        'MMMMd',
        'MMMMEd',
        'mmss',
        'ms',
        'y',
        'yM',
        'yMd',
        'yMEd',
        'yMM',
        'yMMM',
        'yMMMd',
        'yMMMEd',
        'yMMMM',
        'yQQQ',
        'yQQQQ'
    );

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        // Locale mặc định
        $this->locale = 'en_VN';
    }

    /**
     * Thiết lập Locale
     *
     * @param string $locale Locale
     */
    public function setLocale($locale = null)
    {
        if (null !== $locale)
            $this->locale = $locale;
    }

    /**
     * Xuất ngày theo các kiểu định dạng có sẵn trong PHP
     *
     * @param mixed $value
     * @param int $style Kiểu
     * @return string
     */
    public function date($value, $style)
    {
        return $this->dateTime($value, $style, IntlDateFormatter::NONE);
    }

    /**
     * Xuất giờ theo các kiểu định dạng có sẵn trong PHP
     *
     * @param mixed $value
     * @param int $style Kiểu
     * @return string
     */
    public function time($value, $style)
    {
        return $this->dateTime($value, IntlDateFormatter::NONE, $style);
    }

    /**
     * Xuất ngày giờ theo các kiểu định dạng có sẵn trong PHP
     *
     * @param type $value
     * @param type $dateStyle
     * @param type $timeStyle
     * @return string
     */
    public function dateTime($value, $dateStyle, $timeStyle)
    {
        $formatter = md5($this->locale . $dateStyle . $timeStyle);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = new IntlDateFormatter($this->locale, $dateStyle, $timeStyle);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        //$this->_formatters[$formatter]->setPattern(null);
        $string = $this->formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function customPattern($value, $pattern)
    {
        $formatter = md5($this->locale . 'custom');

        // Is it pattern id
        if (in_array($pattern, $this->dateTimePatternId)) {
            $pattern = I18nResource::datePattern($pattern, '', $this->locale);
        } else if (strpos('|', $pattern) > 0) {
            $patterns = explode('|', $pattern);
            if (count($patterns) === 2) {
                if (in_array($patterns[0], $this->dateTimePatternId) && in_array($patterns[1], $this->dateTimePatternId)) {
                    $pattern = I18nResource::datePattern($patterns[0], $patterns[1], $this->locale);
                }
            }
        }

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = new IntlDateFormatter($this->locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        $this->formatters[$formatter]->setPattern($pattern);

        $string = $this->formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function appendPattern($value, $pattern, $item)
    {
        $formatter = md5($this->locale . 'custom');

        // Is it pattern id
        if (in_array($pattern, $this->dateTimePatternId)) {
            $pattern = I18nResource::getDateTimePattern($pattern, '', $this->locale);
        } else if (strpos('|', $pattern) > 0) {
            $patterns = explode('|', $pattern);
            if (count($patterns) === 2) {
                if (in_array($patterns[0], $this->dateTimePatternId) && in_array($patterns[1], $this->dateTimePatternId)) {
                    $pattern = I18nResource::getDateTimePattern($patterns[0], $patterns[1], $this->locale);
                }
            }
        }

        $itemPattern = array(
            'Week' => 'ww'
        );

        $appendPattern = I18nResource::getDateTimeWithAppendItems($item, $pattern, $itemPattern[$item], $this->locale);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = new IntlDateFormatter($this->locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        }

        if (is_string($value)) {
            $value = strtotime($value);
            if ($value === false)
                return '';
        }

        echo $appendPattern;

        $this->formatters[$formatter]->setPattern($appendPattern);

        $string = $this->formatters[$formatter]->format($value);
        return $string === false ? '' : $string;
    }

    public function relative($relative, $date, $dateStyle = 3, $timeStyle = 3, $pattern = '')
    {
        if (isset($relative['d'])) {
            $day = I18nResource::dateField('day', $this->locale);
            return $day[$relative['d']];
        }

        if (isset($relative['w'])) {
            $day = I18nResource::dateField('week', $this->locale);
            return $this->customPattern($date, 'EEEE') . ' ' . $day[$relative['w']];
        }

        if (isset($relative['m'])) {
            $day = I18nResource::dateField('month', $this->locale);
            return $this->customPattern($date, I18nResource::datePattern('d', '', $this->locale)) . ' ' . $day[$relative['m']];
        }

        if (isset($relative['y'])) {
            $day = I18nResource::dateField('year', $this->locale);
            return $this->customPattern($date, I18nResource::datePattern('Md', '', $this->locale)) . ' ' . $day[$relative['y']];
        }

        if ($pattern == '') {
            return $this->dateTime($date, $dateStyle, $timeStyle);
        } else {
            return $this->customPattern($date, $pattern);
        }
    }

    public function yearMonth($value)
    {
        return $this->customPattern($value, 'MMMM y');
    }

    public function yearWeek($value)
    {
        $w = I18nResource::dateField('week', $this->locale);

        return $this->customPattern($value, '\'' . $w['displayName'] . '\' ww y');
    }
}
