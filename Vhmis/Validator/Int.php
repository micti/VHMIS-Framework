<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

use \NumberFormatter;

/**
 * Integer validator.
 */
class Int extends ValidatorAbstract
{
    /**
     * Not integer code
     */
    const NOT_INT = 3;

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOT_INT => 'Value is not integer number.'
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
     * @return Int
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

        if (is_int($value)) {
            $this->standardValue = $value;
            return true;
        }

        return $this->isInt($value);
    }

    /**
     * Validate allow type of value.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    protected function isValidType($value) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->setNotValidInfo(self::NOT_INT, $this->messages[self::NOT_INT]);
            return false;
        }

        return true;
    }

    /**
     * Validate integer string or integer float
     *
     * @param mixed $value
     *
     * @return boolean
     */
    protected function isInt($value)
    {
        $format = new NumberFormatter($this->options['locale'], NumberFormatter::DECIMAL);

        $parsedInt = $format->parse($value, NumberFormatter::TYPE_INT64);
        if (intl_is_failure($format->getErrorCode())) {
            $this->setNotValidInfo(self::NOT_INT, $this->messages[self::NOT_INT]);
            return false;
        }

        // Format lại $value
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);

        // Kiểm tra lại
        if (strval($parsedInt) !== $valueFiltered) {
            $this->setNotValidInfo(self::NOT_INT, $this->messages[self::NOT_INT]);
            return false;
        }

        $this->standardValue = $parsedInt;
        return true;
    }
}
