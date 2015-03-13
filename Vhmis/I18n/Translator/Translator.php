<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Translator;

use Vhmis\I18n\Plural\Plural;

class Translator
{

    /**
     * Message formatter objects.
     *
     * @var \MessageFormatter[]
     */
    protected $messageFormatters = [];

    /**
     * Resource of translated messages.
     *
     * @var array
     */
    protected $resource;

    /**
     * Resource loader.
     *
     * @var Loader\FileLoaderInterface
     */
    protected $loader;

    /**
     * Fallback locale
     * 
     * @var string
     */
    protected $fallbackLocale;

    /**
     * Set loader.
     *
     * @param Loader\FileLoaderInterface $loader
     *
     * @return Translator
     */
    public function setLoader(Loader\FileLoaderInterface $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * Set fallback locale
     *
     * @param string $locale
     * 
     * @return Translator
     */
    public function setFallbackLocale($locale)
    {
        $this->fallbackLocale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getLocale($locale)
    {
        return (!is_string($locale) || $locale === '') ? \Locale::getDefault() : $locale;
    }

    /**
     * Translate.
     *
     * @param string $message
     * @param array  $values
     * @param string $textdomain
     * @param string $locale
     *
     * @return string
     */
    public function translate($message, $values, $textdomain = 'Default', $locale = '')
    {
        $locale = $this->getLocale($locale);

        $translatedMessage = $this->getTranslatedMessages($message, $locale, $textdomain);

        if (is_array($values)) {
            $translatedMessage = $this->formatMessage($translatedMessage, $values, $locale);
        }

        return $translatedMessage;
    }

    /**
     * Plural translate.
     *
     * @param string     $message
     * @param int|double $nvalue
     * @param array      $values
     * @param string     $textdomain
     * @param string     $locale
     *
     * @return string
     */
    public function translatePlural($message, $nvalue, $values = null, $textdomain = 'Default', $locale = '')
    {
        $locale = $this->getLocale($locale);
        $type = Plural::type($nvalue, $locale);

        $translatedMessage = $this->getTranslatedMessages($message . '.' . $type, $locale, $textdomain);

        if (is_array($values)) {
            $translatedMessage = $this->formatMessage($translatedMessage, $values, $locale);
        }

        return $translatedMessage;
    }

    /**
     * Format message.
     *
     * @param string $message
     * @param array  $values
     * @param string $locale
     *
     * @return string
     */
    protected function formatMessage($message, $values, $locale)
    {
        $formatter = $this->getMessageFormatter($locale);
        $formatter->setPattern($message);
        $formatString = $formatter->format($values);

        if (!$formatString) {
            return '';
        }

        return $formatString;
    }

    /**
     * Get resource messages.
     *
     * @param string $locale
     * @param string $textdomain
     *
     * @return string
     */
    protected function getTranslatedMessages($message, $locale, $textdomain)
    {
        if (!isset($this->resource[$locale][$textdomain])) {
            $this->resource[$locale][$textdomain] = $this->loader->load($locale, $textdomain);
        }

        if (!isset($this->resource[$locale][$textdomain][$message])) {
            if (isset($this->fallbackLocale) && $this->fallbackLocale !== $locale) {
                return $this->getTranslatedMessages($message, $this->fallbackLocale, $textdomain);
            }

            return $message;
        }

        return $this->resource[$locale][$textdomain][$message];
    }

    /**
     * Get message formatter.
     *
     * @param string $locale
     *
     * @return \MessageFormatter
     */
    protected function getMessageFormatter($locale)
    {
        if (!isset($this->messageFormatters[$locale])) {
            $this->messageFormatters[$locale] = new \MessageFormatter($locale, 'emptypattern');
        }

        return $this->messageFormatters[$locale];
    }
}
