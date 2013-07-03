<?php

namespace Vhmis\Validator;

use \NumberFormatter;
use Vhmis\Config\Configure;

/**
 * Kiểm tra số thập phân
 */
class Float extends ValidatorAbstract
{
    /**
     * Locale
     *
     * @var string
     */
    protected $locale;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        $this->locale = Configure::get('Locale') === null ? 'en_US' : Configure::get('Locale');
    }

    /**
     * Thiết lập
     *
     * @param type $options
     * @return \Vhmis\Validator\ValidatorAbstract
     */
    public function setOptions($options)
    {
        if(isset($options['locale'])) {
            $this->locale = $options['locale'];
        }

        return $this;
    }

    /**
     * Kiểm tra xem giá trị có phải là số thập phân không (Có dựa theo locale)
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value, $params = null)
    {
        $this->value = $value;

        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return false;
        }

        if (is_int($value)) {
            $this->standardValue = $value;
            return true;
        }

        if (is_float($value)) {
            $this->standardValue = $value;
            return true;
        }

        $format = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);

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
        while (strpos($valueFiltered, '.') !== false && (substr($valueFiltered, -1) == '0' || substr($valueFiltered, -1) == '.')) {
            $valueFiltered = substr($valueFiltered, 0, strlen($valueFiltered) - 1);
        }

        // Kiểm tra lại
        if (strval($parsedFloat) !== $valueFiltered) {
            return false;
        }

        $this->standardValue = $parsedFloat;
        return true;
    }
}
