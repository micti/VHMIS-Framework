<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\DateTime;

use Vhmis\DateTime\DateRepeat\Rule;
use Vhmis\DateTime\DateRepeat\AbstractRepeat;

class DateRepeat
{
    /**
     *
     * @var Rule
     */
    protected $rule;

    /**
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
        '4' => 'Vhmis\DateTime\DateRepeat\Day',
        '5' => 'Vhmis\DateTime\DateRepeat\Week',
        '6' => 'Vhmis\DateTime\DateRepeat\Month',
        '7' => 'Vhmis\DateTime\DateRepeat\Year'
    );

    /**
     * Start day of week
     * 
     * @var int
     */
    protected $startOfWeek = 1;

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

            if($info['by'] === 5) {
                $this->repeats[$info['by']]->setStartDayOfWeek($this->startOfWeek);
            }
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

    /**
     * Set start day of week
     * 
     * @param int $day
     *
     * @return \Vhmis\DateTime\DateRepeat
     */
    public function setDayOfWeek($day)
    {
        $this->startOfWeek = (int) $day;

        if(isset($this->repeats['5'])) {
            $this->repeats['5']->setStartDayOfWeek($day);
        }

        return $this;
    }
}
