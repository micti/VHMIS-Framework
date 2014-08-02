<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime;

use Vhmis\I18n\DateTime\DateRepeat\Rule;
use Vhmis\I18n\DateTime\DateRepeat\AbstractRepeat;

class DateRepeat
{
    /**
     * Rule object
     *
     * @var Rule
     */
    protected $rule;

    /**
     * Repeat object (day, week, month, year)
     *
     * @var AbstractRepeat[]
     */
    protected $repeats;

    /**
     * Repeat class name
     *
     * @var array
     */
    protected $repeatClass = array(
        '4' => 'Vhmis\I18n\DateTime\DateRepeat\Day',
        '5' => 'Vhmis\I18n\DateTime\DateRepeat\Week',
        '6' => 'Vhmis\I18n\DateTime\DateRepeat\Month',
        '7' => 'Vhmis\I18n\DateTime\DateRepeat\Year'
    );

    /**
     * Construct
     */
    public function __construct()
    {
        $this->rule = new Rule();
    }

    /**
     * Get all repeat dates in range
     *
     * @param string $fromDate
     * @param string $toDate
     *
     * @return array
     */
    public function repeatedDates($fromDate, $toDate)
    {
        return $this->getRepeat()->setRule($this->rule)->repeatedDates($fromDate, $toDate);
    }

    /**
     * Get end date of repeat
     *
     * @return string
     */
    public function endDate()
    {
        return $this->getRepeat()->setRule($this->rule)->endDate();
    }

    /**
     * Get repeat object
     *
     * @return AbstractRepeat
     */
    public function getRepeat()
    {
        $info = $this->rule->getInfo();

        if (!isset($this->repeats[$info['by']])) {
            $this->repeats[$info['by']] = new $this->repeatClass[$info['by']];
        }

        return $this->repeats[$info['by']];
    }

    /**
     * Get rule to set
     *
     * @return Rule
     */
    public function getRule()
    {
        return $this->rule;
    }
}
