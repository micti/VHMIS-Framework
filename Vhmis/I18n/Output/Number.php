<?php

namespace Vhmis\I18n\Output;

use \NumberFormatter;

/**
 * Class dùng để định dạng các chuỗi đại diện cho số hoặc số theo Locale
 *
 * @package Vhmis_I18n
 * @subpackage Output
 */
class Number
{

    /**
     * Locale
     *
     * @var string
     */
    protected $locale;

    /**
     * Các đối tượng NumberFormatter, ứng với mỗi cặp locale và format style
     */
    protected $_formatters = array();

    public function __construct()
    {
        // Locale mặc định
        $this->locale = locale_get_default();
    }

    /**
     * Thiết lập Locale
     *
     * @param string $locale Locale
     */
    public function setLocale($locale = null)
    {
        if (null !== $locale)
            $this->locale = locale;
    }

    /**
     * Định dạng số thực
     *
     * @param mixed $value Giá trị cần định dạng
     * @return string
     */
    public function float($value)
    {
        $style = NumberFormatter::DECIMAL;
        $formatter = md5($this->locale . $style);

        if (!isset($this->_formatters[$formatter]))
            $this->_formatters[$formatter] = NumberFormatter::create($this->locale, $style);

        return $this->_formatters[$formatter]->format($value, NumberFormatter::TYPE_DOUBLE);
    }

    /**
     * Định dạng số nguyên
     *
     * @param mixed $value Giá trị cần định dạng
     * @return string
     */
    public function interger($value)
    {
        $style = NumberFormatter::DECIMAL;
        $formatter = md5($this->locale . $style);

        if (!isset($this->_formatters[$formatter]))
            $this->_formatters[$formatter] = NumberFormatter::create($this->locale, $style);

        return $this->_formatters[$formatter]->format($value, NumberFormatter::TYPE_INT64);
    }

    /**
     * Định dạng phần trăm
     *
     * @param mixed $value Giá trị cần định dạng
     * @return string
     */
    public function percent($value) {
        $style = NumberFormatter::PERCENT;
        $formatter = md5($this->locale . $style);

        if (!isset($this->_formatters[$formatter]))
            $this->_formatters[$formatter] = NumberFormatter::create($this->locale, $style);

        return $this->_formatters[$formatter]->format($value);
    }

    /**
     * Định dạng tiền tệ
     *
     * @param mixed $value Giá trị cần định dạng
     * @return string
     */
    public function currency($value, $currency)
    {
        $style = NumberFormatter::CURRENCY;
        $formatter = md5($this->locale . $style);

        if (!isset($this->_formatters[$formatter]))
            $this->_formatters[$formatter] = NumberFormatter::create($this->locale, $style);

        return $this->_formatters[$formatter]->formatCurrency($value, $currency);
    }

    /**
     * Định dạng chữ cho số
     *
     * @param mixed $value
     * @return string
     * @todo Hiện xuất ra tiếng Việt vẫn còn lỗi mươi -> mười, kiểm tra phiên
     *       bản mới hoặc viết riêng hàm
     */
    public function string($value)
    {
        $style = NumberFormatter::SPELLOUT;
        $formatter = md5($this->locale . $style);

        if (!isset($this->_formatters[$formatter]))
            $this->_formatters[$formatter] = NumberFormatter::create($this->locale, $style);

        return $this->_formatters[$formatter]->format($value);
    }
}