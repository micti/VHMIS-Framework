<?php
namespace Vhmis\Validator;
use NumberFormatter;
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
        if (! is_string($value) && ! is_int($value) && ! is_float($value)) {
            $this->_setMessage('Số thập phân không đúng kiểu', '-1', 'floatnottype');
            return false;
        }
        
        if (is_int($value)) {
            $this->_standardValue = $value;
            return true;
        }
        
        if (is_float($value)) {
            $this->_standardValue = $value;
            return true;
        }
        
        $format = new NumberFormatter($this->_locale, NumberFormatter::DECIMAL);
        
        $parsedFloat = $format->parse($value, NumberFormatter::TYPE_DOUBLE);
        if (intl_is_failure($format->getErrorCode())) {
            $this->_setMessage('Số thập phân không hợp lệ', '-2', 'floatnotvalid');
            return false;
        }
        
        // Format lại $value
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        
        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);
        
        // Loại bỏ số 0 ở cuối trong phần thập phân hoặc dấu . nếu nằm ở cuối
        while (strpos($valueFiltered, '.') !== false && (substr($valueFiltered, - 1) == '0' || substr($valueFiltered, - 1) == '.')) {
            $valueFiltered = substr($valueFiltered, 0, strlen($valueFiltered) - 1);
        }
        
        // Kiểm tra lại
        if (strval($parsedFloat) !== $valueFiltered) {
            $this->_setMessage('Số thập phân không hợp lệ', '-2', 'floatnotvalid');
            return false;
        }
        
        $this->_standardValue = $parsedFloat;
        return true;
    }
}