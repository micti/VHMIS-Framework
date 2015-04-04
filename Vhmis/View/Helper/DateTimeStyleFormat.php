<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\View\Helper;

use Vhmis\I18n\DateTime\DateTime;

class DateTimeStyleFormat extends DateTimePatternFormat
{

    /**
     * Format.
     *
     * @param DateTime|mixed $date
     * @param int $dateStyle
     * @param int $timeStyle
     * @param string|mixed $timezone
     * @param string $calendar
     * @param string $locale
     *
     * @return string
     */
    public function __invoke($date, $dateStyle = 3, $timeStyle = 3, $timezone = null, $calendar = '', $locale = '')
    {
        if (!($date instanceof DateTime)) {
            $date = $this->getDate($date, $timezone, $calendar);
        }

        return $this->dtFormatter->style($date, $dateStyle, $timeStyle, $locale);
    }
}
