<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\Resource\Resource;

class DateTimeFieldName extends HelperAbstract
{

    /**
     *
     * @param string $field
     * @param string $width
     * @param string $locale
     *
     * @return string
     */
    public function __invoke($field, $width = '', $locale = '')
    {
        $field .= $width === '' ? '' : '-' . $width;
        return Resource::getDateField($field, $locale)['displayName'];
    }
}
