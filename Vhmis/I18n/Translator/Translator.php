<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Translator;

class Translator
{

    /**
     * Resource of translated messages
     *
     * @var array
     */
    protected $resource;

    /**
     * Resource loader
     *
     * @var Loader\LoaderInterface
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
}
