<?php

namespace Vhmis\Validator;

use \NumberFormatter;
use Vhmis\Config\Configure;

/**
 * Kiểm tra số nguyên
 */
class Int extends ValidatorAbstract
{

    /**
     * Locale
     *
     * @var string
     */
    protected $_locale;

    public function __construct($options = null)
    {
        $this->_locale = '';

        if (is_array($options)) {
            if (isset($options['locale']))
                $this->_locale = $options['locale'];
        }

        if ($this->_locale === '')
            $this->_locale = Configure::get('Locale');
    }

    public function isValid($value, $params = null)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->_setMessage('Số nguyên không đúng kiểu', static::INTNOTTYPE, 'intnottype');
            return false;
        }

        if (is_int($value)) {
            $this->_standardValue = $value;
            return true;
        }

        $format = new NumberFormatter($this->_locale, NumberFormatter::DECIMAL);

        $parsedInt = $format->parse($value, NumberFormatter::TYPE_INT64);
        if (intl_is_failure($format->getErrorCode())) {
            $this->_setMessage('Số nguyên không hợp lệ', static::INTNOTVALID, 'intnotvalid');
            return false;
        }

        // Format lại $value
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);

        // Kiểm tra lại
        if (strval($parsedInt) !== $valueFiltered) {
            $this->_setMessage('Số nguyên không hợp lệ', static::INTNOTVALID, 'intnotvalid');
            return false;
        }

        $this->_standardValue = $parsedInt;
        return true;
    }
}