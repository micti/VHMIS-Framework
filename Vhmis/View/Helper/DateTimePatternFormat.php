<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\View\Helper;

use Vhmis\I18n\Formatter\DateTime as dtFormatter;
use Vhmis\I18n\DateTime\DateTime;

class DateTimePatternFormat extends AbstractDateTime
{

    /**
     * DateTime formatter object.
     *
     * @var dtFormatter
     */
    protected $dtFormatter;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->dtFormatter = new dtFormatter;
    }

    /**
     * Format.
     *
     * @param DateTime|mixed $date
     * @param string $pattern
     * @param string|mixed $timezone
     * @param string $calendar
     * @param string $locale
     *
     * @return string
     */
    public function __invoke($date, $pattern, $timezone = null, $calendar = '', $locale = '')
    {
        if (!($date instanceof DateTime)) {
            $date = $this->getDate($date, $timezone, $calendar);
        }

        return $this->dtFormatter->pattern($date, $pattern, $locale);
    }
}
