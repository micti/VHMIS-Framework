<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Vhmis\I18n\Formatter;

use Vhmis\I18n\DateTime\DateTime;
use \IntlDateFormatter;

class DateTime
{

    /**
     * Pattern id list
     *
     * @var array
     */
    protected $patternIds = array(
        'd',
        'Ed',
        'Gy',
        'GyMMM',
        'GyMMMd',
        'GyMMMEd',
        'h',
        'H',
        'hm',
        'Hm',
        'hms',
        'Hms',
        'M',
        'Md',
        'MEd',
        'MMdd',
        'MMM',
        'MMMd',
        'MMMEd',
        'MMMMd',
        'MMMMEd',
        'mmss',
        'ms',
        'y',
        'yM',
        'yMd',
        'yMEd',
        'yMM',
        'yMMM',
        'yMMMd',
        'yMMMEd',
        'yMMMM',
        'yQQQ',
        'yQQQQ'
    );

    /**
     * Intl datetime formatter objects
     */
    protected $formatters;

    /**
     * Format datetime string by style
     *
     * @param DateTime $datetime
     * @param int|null $dateStyle
     * @param int|null $timeStyle
     * 
     * @return string
     */
    public function style($datetime, $dateStyle = 3, $timeStyle = 3, $locale = '')
    {
        if ($locale === '') {
            $locale = \Locale::getDefault();
        }

        $datetime->format([$dateStyle, $timeStyle], $locale);
    }
    
    public function pattern($datetime, $pattern, $locale)
    {
        
    }
}
