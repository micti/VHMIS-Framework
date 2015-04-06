<?php

namespace Vhmis\View\Helper;

use Vhmis\I18n\Resource\Resource;

class CalendarFieldName extends HelperAbstract
{

    /**
     *
     * @param string $field
     * @param string $value
     * @param string $width
     * @param string $calendar
     * @param string $locale
     *
     * @return string
     */
    public function __invoke($field, $value, $width = 'wide', $calendar = 'gregorian', $locale = '')
    {
        return Resource::getCalendarField($field, $value, $width, 'stand-alone', $calendar, $locale);
    }
}
