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
use \Vhmis\DateTime\DateTime as VhDateTime;

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
     *
     * @var \Vhmis\DateTime\DateTime;
     */
    protected $dateFirst;

    /**
     *
     * @var \Vhmis\DateTime\DateTime;
     */
    protected $dateSecond;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        // Locale mặc định
        $this->locale = locale_get_default();

        $this->dateFirst = new VhDateTime;
        $this->dateSecond = new VhDateTime;
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
            $this->dateFirst->modify($value1);
            $this->dateSecond->modify($value2);

            if ($this->dateFirst > $this->dateSecond) {
                $this->dateFirst->modify($value2);
                $this->dateSecond->modify($value1);
            }

            $diff = $this->dateFirst->diff($this->dateSecond);
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

    public function unit($number, $field)
    {
        $unitsPattern = I18nResource::units($field, $this->locale);
        $type = I18nPlurals::type($number, $this->locale);
        return str_replace('{0}', $number, $unitsPattern['unitPattern-count-' . $type]);
    }
}
