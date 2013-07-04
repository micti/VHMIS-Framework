<?php

namespace Vhmis\I18n\Translator;

class Translator
{
    protected $locale;

    protected $dictionary;

    /**
     *
     * @param type $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Translate
     *
     * @param type $message
     * @param type $textdomain
     * @param type $locale
     * @return type
     */
    public function translate($message, $textdomain, $locale)
    {
        if(!isset($this->dictionary[$locale][$textdomain][$message])) {
            return $message;
        }

        return $this->dictionary[$locale][$textdomain][$message];
    }

    /**
     * Tên ngắn gọn để gọi translate
     *
     * @param type $message
     * @param type $textdomain
     * @param type $locale
     * @return type
     */
    public function __($message, $textdomain, $locale)
    {
        return $this->translate($message, $textdomain, $locale);
    }

    /**
     * Tên ngắn gọn để xuất translate
     *
     * @param type $message
     * @param type $textdomain
     * @param type $locale
     */
    public function _e($message, $textdomain, $locale)
    {
        echo $this->translate($message, $textdomain, $locale);
    }
}
