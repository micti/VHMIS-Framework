<?php

namespace Vhmis\I18n\Translator\Loader;

interface FileLoaderInterface
{
    /**
     * Set path
     * 
     * @params string $path
     */
    public function setPath($path);
    
    /**
     * Load
     * 
     * @params string $path
     * @params string $domain
     *
     * @return array
     */
    public function load($locale, $domain);
}