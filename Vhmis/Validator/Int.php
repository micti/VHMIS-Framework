<?php

namespace Vhmis\Validator;

use \NumberFormatter;
use Vhmis\Config\Configure;

/**
 * Kiểm tra số nguyên
 */
class Int extends ValidatorAbstract
{
    const NOTINT = 'notarray';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOTINT => 'Giá trị không phải là số nguyên'
    );

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
     * Kiểm tra xem giá trị có phải là số nguyên không (Có dựa theo locale)
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->setMessage(self::NOTINT);
            return false;
        }

        if (is_int($value)) {
            $this->standardValue = $value;
            return true;
        }

        $format = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);

        $parsedInt = $format->parse($value, NumberFormatter::TYPE_INT64);
        if (intl_is_failure($format->getErrorCode())) {
            $this->setMessage(self::NOTINT);
            return false;
        }

        // Format lại $value
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);

        // Kiểm tra lại
        if (strval($parsedInt) !== $valueFiltered) {
            $this->setMessage(self::NOTINT);
            return false;
        }

        $this->standardValue = $parsedInt;
        return true;
    }
}
