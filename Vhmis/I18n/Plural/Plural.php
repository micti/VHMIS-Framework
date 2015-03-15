<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Plural;

use \Vhmis\I18n\Resource\Resource as I18nResource;

class Plural
{

    /**
     * Lấy plural type
     *
     * other|one|many|few|two
     *
     * @param int|double|float|string $number
     * @param string $locale
     *
     * @return string
     */
    public static function getCardinalType($number, $locale = '')
    {
        $rules = I18nResource::getCardinalPluralRule($locale);

        foreach ($rules as $type => $rule) {
            if (Parser::isAccept($number, $rule)) {
                return str_replace('pluralRule-count-', '', $type);
            }
        }

        return 'other';
    }
}
