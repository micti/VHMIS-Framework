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
 * Number abtract validator.
 */
abstract class NumberAbstract extends ValidatorAbstract
{
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
     * Validate allow type of value.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    protected function isValidType($value) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return false;
        }

        return true;
    }

    /**
     * Validate integer string or integer float
     *
     * @param string $type Float or Integer
     * @param mixed $value
     *
     * @return boolean
     */
    protected function isNumber($type, $value)
    {
        $praseType = NumberFormatter::TYPE_INT64;
        if ($type === 'float') {
            $praseType = NumberFormatter::TYPE_DOUBLE;
        }

        $format = new NumberFormatter($this->options['locale'], NumberFormatter::DECIMAL);

        $praseValue = $format->parse($value, $praseType);
        if (intl_is_failure($format->getErrorCode())) {
            return false;
        }

        // Convert to standard value.
        $decimalSep = $format->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupingSep = $format->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
        $valueFiltered = str_replace($groupingSep, '', $value);
        $valueFiltered = str_replace($decimalSep, '.', $valueFiltered);

        // Remove 0 or . at the end
        while (
            strpos($valueFiltered, '.') !== false &&
            (substr($valueFiltered, -1) == '0' || substr($valueFiltered, -1) == '.')
        ) {
            $valueFiltered = substr($valueFiltered, 0, strlen($valueFiltered) - 1);
        }

        // Check again
        if (strval($praseValue) !== $valueFiltered) {
            return false;
        }

        $this->standardValue = $praseValue;
        return true;
    }
}
