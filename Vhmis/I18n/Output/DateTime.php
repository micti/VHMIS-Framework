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
use \Vhmis\I18n\Plurals\Plurals as I18nPlurals;

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
     * Locale
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
     *
     * @var \Vhmis\DateTime\DateTime;
     */
    protected $date1;

    /**
     *
     * @var \Vhmis\DateTime\DateTime;
     */
    protected $date2;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        // Locale mặc định
        $this->locale = locale_get_default();

        $this->date1 = new \Vhmis\DateTime\DateTime;
        $this->date2 = new \Vhmis\DateTime\DateTime;
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

    /**
     * Xuất ngày giờ theo các pattern tự tạo hoặc dựa trên formatId từ dữ liệu CLDR
     * Nếu muốn xuất cả ngày và giờ theo formatId thì truyền vào formatID của ngày và giờ, được phân cách bằng ký tự |
     *
     * @param string $value
     * @param string $pattern
     * @return string
     */
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

    /**
     * Lấy tên của tháng hoặc ngày trong tuần
     *
     * @param string $field
     * @param string $type
     * @param string $format
     * @return string
     */
    public function calendarFieldName($value, $field, $type, $format)
    {
        if ($field !== 'months' && $field !== 'days') {
            return '1';
        }

        if ($type !== 'format' && $type !== 'stand-alone') {
            return '2';
        }

        $data = I18nResource::calendarField($field, $this->locale);

        if (isset($data[$type][$format])) {
            return $data[$type][$format][$value];
        }

        return '';
    }

    public function dateFieldName($field, $format = 'displayName')
    {
        $data = I18nResource::dateField($field, $this->locale);

        return $data[$format];
    }

    /**
     * Hiển thị ngày giờ theo quan hệ với 1 ngày nào đó
     * Nếu không $relative không nhận giá trị hợp lệ, sẽ xuất ra ngày giờ bình thường theo style hoặc pattern
     *
     *
     * @param array $relative Kết quả được từ phương thức \Vhmis\DateTime\DateTime::relative()
     * @param string $date Ngày cần xuất kết quả
     * @param int $dateStyle Kiểu ngày được định dạng sẵn bởi PHP
     * @param int $timeStyle Kiểu giờ được định dạng sẵn bởi PHP
     * @param string $pattern Pattern hoặc FormatId
     * @return string
     */
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

    /**
     * Xuất định dạng tháng và năm
     *
     * @param string $value
     * @return string
     */
    public function yearMonth($value)
    {
        return $this->customPattern($value, 'MMMM y');
    }

    /**
     * Xuất định dạng tuần của năm
     *
     * @param string $value
     * @return string
     */
    public function yearWeek($value)
    {
        $w = I18nResource::dateField('week', $this->locale);

        return $this->customPattern($value, '\'' . $w['displayName'] . ':\' ww - Y');
    }

    /**
     * Xuất khoảng thời gian
     *
     * @param string $value1
     * @param string $value2
     * @param string $pattern
     * @return type
     */
    public function range($value1, $value2, $pattern)
    {
        $this->date1->modify($value1);
        $this->date2->modify($value2);

        if ($this->date1 > $this->date2) {
            $this->date1->modify($value2);
            $this->date2->modify($value1);
        }

        $interval = $this->date1->findInterval($this->date2);

        // Tìm interval cho ngày
        $intervalField = '';

        if ($interval['y'] !== 0 && strpos($pattern, 'y') !== false) {
            $intervalField = 'y';
        } else {
            if ($interval['M'] !== 0 && strpos($pattern, 'M') !== false) {
                $intervalField = 'M';
            } else {
                if ($interval['d'] !== 0 && strpos($pattern, 'd') !== false) {
                    $intervalField = 'd';
                }
            }
        }

        // Tìm interval cho giờ
        if ($interval['H'] !== 0 && strpos($pattern, 'H') !== false) {
            $intervalField = 'H';
        } else {
            if (strpos($pattern, 'h') !== false) {
                if ($interval['a'] !== 0) {
                    $intervalField = 'a';
                } else {
                    $intervalField = 'h';
                }
            } else {
                if ($interval['m'] !== 0 && strpos($pattern, 'm') !== false) {
                    $intervalField = 'm';
                }
            }
        }

        $data = I18nResource::dateIntervalPattern($pattern, $intervalField, $this->locale);

        $value1 = $this->customPattern($this->date1->formatISO(1), $data['patternbegin']);
        $value2 = $this->customPattern($this->date2->formatISO(1), $data['patternend']);

        $value = str_replace('{0}', $value1, $data['pattern']);
        $value = str_replace('{1}', $value2, $value);

        return $value;
    }

    /**
     * Xuất khoảng cách thời gian tính theo số năm, số ngày ....
     *
     * @param \Interval|string $value1
     * @param string $value2
     * @param string $pattern
     * @return type
     */
    public function interval($value1, $value2 = null)
    {
        if ($value1 instanceof \DateInterval) {
            $diff = $value1;
        } else {
            $this->date1->modify($value1);
            $this->date2->modify($value2);

            if ($this->date1 > $this->date2) {
                $this->date1->modify($value2);
                $this->date2->modify($value1);
            }

            $diff = $this->date1->diff($this->date2);
        }
        
        $interval = array();

        if ($diff->y != 0) {
            $type = I18nPlurals::type($diff->y, $this->locale);
            $unitsPattern = I18nResource::units('year', $this->locale);
            $interval[] = str_replace('{0}', $diff->y, $unitsPattern['unitPattern-count-' . $type]);
        }

        if ($diff->m != 0) {
            $type = I18nPlurals::type($diff->m, $this->locale);
            $unitsPattern = I18nResource::units('month', $this->locale);
            $interval[] = str_replace('{0}', $diff->m, $unitsPattern['unitPattern-count-' . $type]);
        }

        if ($diff->d != 0) {
            $type = I18nPlurals::type($diff->d, $this->locale);
            $unitsPattern = I18nResource::units('day', $this->locale);
            $interval[] = str_replace('{0}', $diff->d, $unitsPattern['unitPattern-count-' . $type]);
        }

        if ($diff->h != 0) {
            $type = I18nPlurals::type($diff->h, $this->locale);
            $unitsPattern = I18nResource::units('hour', $this->locale);
            $interval[] = str_replace('{0}', $diff->h, $unitsPattern['unitPattern-count-' . $type]);
        }

        if ($diff->i != 0) {
            $type = I18nPlurals::type($diff->i, $this->locale);
            $unitsPattern = I18nResource::units('minute', $this->locale);
            $interval[] = str_replace('{0}', $diff->i, $unitsPattern['unitPattern-count-' . $type]);
        }

        return implode(' ', $interval);
    }

    public function ago($date, $rootDate = '')
    {
        $this->date1->modify($date);

        if ($rootDate === '') {
            $this->date2->setNow();
        } else {
            $this->date2->modify($rootDate);
        }

        $diff = $this->date1->diff($this->date2);

        if ($diff->invert === 1) {
            return '';
        }

        if ($diff->y != 0) {
            $type = I18nPlurals::type($diff->y, $this->locale);
            $unitsPattern = I18nResource::units('year-past', $this->locale);
            return str_replace('{0}', $diff->y, $unitsPattern['unitPattern-count-' . $type]);
        } else if ($diff->m != 0) {
            $type = I18nPlurals::type($diff->m, $this->locale);
            $unitsPattern = I18nResource::units('month-past', $this->locale);
            return str_replace('{0}', $diff->m, $unitsPattern['unitPattern-count-' . $type]);
        } else if ($diff->d != 0) {
            $type = I18nPlurals::type($diff->d, $this->locale);
            $unitsPattern = I18nResource::units('day-past', $this->locale);
            return str_replace('{0}', $diff->d, $unitsPattern['unitPattern-count-' . $type]);
        } else if ($diff->h != 0) {
            $type = I18nPlurals::type($diff->h, $this->locale);
            $unitsPattern = I18nResource::units('hour-past', $this->locale);
            return str_replace('{0}', $diff->h, $unitsPattern['unitPattern-count-' . $type]);
        } else if ($diff->i != 0) {
            $type = I18nPlurals::type($diff->i, $this->locale);
            $unitsPattern = I18nResource::units('minute-past', $this->locale);
            return str_replace('{0}', $diff->i, $unitsPattern['unitPattern-count-' . $type]);
        } else {
            $type = I18nPlurals::type($diff->s, $this->locale);
            $unitsPattern = I18nResource::units('second-past', $this->locale);
            return str_replace('{0}', $diff->s, $unitsPattern['unitPattern-count-' . $type]);
        }
    }
}
