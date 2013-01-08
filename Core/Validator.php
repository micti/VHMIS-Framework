<?php

use Vhmis\Config\Configure;

/**
 * Các loại cần kiểm tra
 *
 * 1. Không rỗng (rỗng là chuỗi rỗng hoặc chỉ gồm khoảng trắng, tab, xuống dòng ...)
 * 2. Alnum : chỉ bao gồm chữ và số
 * 3. Digit : chỉ bao gồm số
 * 4. Slug : chỉ bao gồm a-z,-,_
 * 5. Range : chỉ nằm trông một khoảng nào đó
 */
class Vhmis_Validator
{

    /**
     * Đối tượng Vhmis_Filter, dùng trong kiểm tra một số trường hợp
     *
     * @var Vhmis_Filter
     */
    protected $_fitler;

    /**
     * Đối tượng Vhmis_Date, dùng trong kiểm tra ngày tháng
     *
     * @var Vhmis_Date
     */
    protected $_date;

    /**
     * Mã ngỗn ngữ quốc gia
     * Lấy từ thông tin Locale được set bởi Vhmis_Config
     * Nếu không có sẽ sử dụng 'en_US'
     *
     * @var string
     */
    protected $_locale;

    public function __construct($filter = null, $date = null)
    {
        if ($filter == null) {
            $this->_filter = new Vhmis_Filter;
        }
        else
            $this->_filter = $filter;

        if ($date == null) {
            $this->_date = new Vhmis_Date;
        }
        else
            $this->_date = $date;

        $this->_locale = Configure::get('Locale');

        if ($this->_locale == null)
            $this->_locale = 'en_US';
    }

    /**
     * Hàm kiểm tra xem một chuỗi có phải là không rỗng hay rỗng
     */
    public function notEmpty($value)
    {
        return $this->_check('/[^\s]+/m', $value);
    }

    /**
     * Hàm kiểm tra xem độ dài của một chuỗi có nằm trong một vùng nào đó không
     */
    public function range($value, $min, $max)
    {
        // TODO : Unicode need check
        $len = mb_strlen($value);

        return ($len >= $min && $len <= $max);
    }

    /**
     * Hàm kiểm tra xem một số có nằm trong một vùng nào đó không
     */
    public function between($value, $min, $max)
    {
        //$value = $this->_filter->digit($value);

        return ($value >= $min && $value <= $max);
    }

    /**
     * Hàm kiểm tra xem có phải là 1 chuỗi gồm số và chữ cái hay không
     */
    public function alnum($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $new = $this->_filter->alnum($value, $allowWhiteSpace, $allowUnicode);

        if ($new !== $value) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Hàm kiểm tra xem có phải là 1 chuỗi gồm chỉ chữ cái hay không
     */
    public function alpha($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $new = $this->_filter->alpha($value, $allowWhiteSpace, $allowUnicode);

        if ($new !== $value) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Kiểm tra xem một chuỗi có phải là số thập phân không (ko xét đến dấu chấm động)
     */
    public function float($value, $locale = '', $returnISO = true, $allowSciNa = false)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return false;
        }

        if (is_int($value)) {
            return $returnISO ? $value : true;
        }

        // Tạm thời, chỉ sử dụng is_float nếu cho phép dấu chấm khoa học
        // đối với các trường hợp khác mặc định float khác, sử dụng phần dưới để
        // kiểm tra
        if (is_float($value) && $allowSciNa == true) {
            return $returnISO ? $value : true;
        }

        if ($locale == '')
            $locale = $this->_locale;
        $format = new NumberFormatter($locale, NumberFormatter::DECIMAL);

        $parsedFloat = $format->parse($value, NumberFormatter::TYPE_DOUBLE);
        if (intl_is_failure($format->getErrorCode())) {
            return false;
        }

        // Format lại $value
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);

        // Loại bỏ số 0 ở cuối trong phần thập phân hoặc dấu . nếu nằm ở cuối
        while (strpos($valueFiltered, '.') !== false
        && (substr($valueFiltered, -1) == '0' || substr($valueFiltered, -1) == '.')
        ) {
            $valueFiltered = substr($valueFiltered, 0, strlen($valueFiltered) - 1);
        }

        // Kiểm tra lại
        if (strval($parsedFloat) !== $valueFiltered) {
            return false;
        }

        return $returnISO ? $parsedFloat : true;
    }

    /**
     * Hàm kiểm tra xem một ngày có hợp lệ hay không
     */
    public function date($value, $format = 'dd/mm/yyyy', $returnISO = true, $allowEmpty = true)
    {
        if ($format == 'dd/mm/yyyy') {
            // Nếu giá trị cần kiểm tra rỗng và cho phép rỗng
            if (trim($value) == '' && $allowEmpty === true) {
                return $returnISO ? '0000-00-00' : true;
            }

            $date = explode('/', $value, 3);

            if (count($date) != 3) {
                echo '12';
                return false;
            }

            if (count(trim($date[2])) <= 2) {
                $date = trim($date[2]) . '-' . trim($date[1]) . '-' . trim($date[0]);
            } else {
                $date = trim($date[2]) . '/' . trim($date[1]) . '/' . trim($date[0]);
            }

            if (!$this->_date->time($date)) {
                echo '11';
                return false;
            } else {
                if ($returnISO) {
                    return $date;
                } else {
                    return true;
                }
            }
        }

        /* TODO : need other format */
        return false;
    }

    protected function _check($pattern, $value)
    {
        if (preg_match($pattern, $value)) {
            return true;
        } else {
            return false;
        }
    }

}