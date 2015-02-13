<?php

namespace Vhmis\I18n\Plural;

use \Vhmis\I18n\Resource\Resource as I18nResource;

class Plural
{

    public function __construct()
    {}

    /**
     * Lấy plural type
     *
     * other|one|many|few|two
     *
     * @param int|double|float|string $number
     * @param string $locale
     * @return string
     */
    public static function type($number, $locale = '')
    {
        $rules = I18nResource::pluralsRule($locale);

        return 'other';
    }
}
