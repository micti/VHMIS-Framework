<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Formatter;

class Number
{

    protected $formatter = [];

    public function format($value, $locale = '')
    {
        $locale = $this->getLocale($locale);
        $style = \NumberFormatter::DECIMAL;
        $formatter = md5($locale . $style);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = \NumberFormatter::create($locale, $style);
        }

        return $this->formatters[$formatter]->format($value, \NumberFormatter::TYPE_DOUBLE);
    }
    
    public function formatInPercent($value, $locale = '')
    {
        $locale = $this->getLocale($locale);
        $style = \NumberFormatter::PERCENT;
        $formatter = md5($locale . $style);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = \NumberFormatter::create($locale, $style);
        }

        return $this->formatters[$formatter]->format($value);
    }
    
    public function formatInCurrency($value, $currency, $locale = '')
    {
        $locale = $this->getLocale($locale);
        $style = \NumberFormatter::CURRENCY;
        $formatter = md5($locale . $style);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = \NumberFormatter::create($locale, $style);
        }

        return $this->formatters[$formatter]->formatCurrency($value, $currency);
    }
    
    public function formatInString($value, $locale = '')
    {
        $locale = $this->getLocale($locale);
        $style = \NumberFormatter::SPELLOUT;
        $formatter = md5($locale . $style);

        if (!isset($this->formatters[$formatter])) {
            $this->formatters[$formatter] = \NumberFormatter::create($locale, $style);
        }

        return $this->formatters[$formatter]->formatCurrency($value);
    }

    protected function getLocale($locale)
    {
        if ($locale === '') {
            $locale = \Locale::getDefault();
        }

        return $locale;
    }
}
