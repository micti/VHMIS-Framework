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
     * Resource of translated messages
     *
     * @var array
     */
    protected $resource;

    /**
     * Resource loader
     *
     * @var Loader\FileLoaderInterface
     */
    protected $loader;

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
     * @param string $textdomain
     * @param string $locale
     *
     * @return string
     */
    public function translate($message, $textdomain = 'Default', $locale = '')
    {
        $locale = $this->getLocale($locale);

        $messages = $this->getTranslatedMessages($locale, $textdomain);
        if (!isset($messages[$message])) {
            return $message;
        }

        return $messages[$message];
    }

    /**
     * Plural translate.
     *
     * @param string $message
     * @param int|double $value
     * @param string $textdomain
     * @param string $locale
     *
     * @return string
     */
    public function translatePlural($message, $value, $textdomain = 'Default', $locale = '')
    {
        $locale = $this->getLocale($locale);
        $type = Plural::type($value, $locale);

        $messages = $this->getTranslatedMessages($locale, $textdomain);
        if (!isset($messages[$message])) {
            return $message;
        }

        return $messages[$message][$type];
    }

    /**
     * Translate by message formatter pattern.
     *
     * @param string $message
     * @param array $values
     * @param string $textdomain
     * @param string $locale
     *
     * @return string
     */
    public function transtaleFormatter($message, $values, $textdomain = 'Default', $locale = '')
    {
        $locale = $this->getLocale($locale);
        $messages = $this->getTranslatedMessages($locale, $textdomain);
        $formatter = $this->getMessageFormatter($locale);

        if (!isset($messages[$message])) {
            return $message;
        }

        $formatter->setPattern($messages[$message]);
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
     * @return array
     */
    protected function getTranslatedMessages($locale, $textdomain)
    {
        if (!isset($this->resource[$locale][$textdomain])) {
            $this->resource[$locale][$textdomain] = $this->loader->load($locale, $textdomain);
        }

        return $this->resource[$locale][$textdomain];
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
