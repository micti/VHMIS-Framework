<?php

namespace Vhmis\Validator;

use \NumberFormatter;

/**
 * Float validator.
 */
class Float extends ValidatorAbstract
{

    const NOT_FLOAT = 3;

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOT_FLOAT => 'Value is not float number.'
    );

    /**
     * Construct. Using locale options.
     */
    public function __construct()
    {
        $this->useLocaleOptions();
    }

    /**
     * Reset validator. Using locale options.
     *
     * @return Float
     */
    public function reset()
    {
        parent::reset();

        return $this->useLocaleOptions();
    }

    /**
     * Validate
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!$this->isValidType($value)) {
            return false;
        }

        if (is_int($value) || is_float($value)) {
            $this->standardValue = (float) $value;
            return true;
        }

        return $this->isFloat($value);
    }

    /**
     * Validate allow type of value.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    protected function isValidType($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->setNotValidInfo(self::NOT_FLOAT, $this->messages[self::NOT_FLOAT]);
            return false;
        }

        return true;
    }

    /**
     * Validate float string
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function isFloat($value)
    {
        $format = new NumberFormatter($this->options['locale'], NumberFormatter::DECIMAL);

        $parsedFloat = $format->parse($value, NumberFormatter::TYPE_DOUBLE);
        if (intl_is_failure($format->getErrorCode())) {
            $this->setMessage(self::NOT_FLOAT);
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
            $this->setMessage(self::NOT_FLOAT);
            return false;
        }

        $this->standardValue = $parsedFloat;
        return true;
    }
}
