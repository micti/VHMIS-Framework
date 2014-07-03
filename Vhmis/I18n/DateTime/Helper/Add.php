<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\I18n\DateTime\DateTime;

/**
 * Add helper for DateTime
 */
class Add extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'addEra'         => 1,
        'addYear'        => 1,
        'addMonth'       => 1,
        'addWeek'        => 1,
        'addDay'         => 1,
        'addHour'        => 1,
        'addMinute'      => 1,
        'addSecond'      => 1,
        'addMillisecond' => 1,
    );

    /**
     * Add era
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addEra($amount)
    {
        return $this->date->addField(0, $amount);
    }

    /**
     * Add year
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addYear($amount)
    {
        return $this->date->addField(1, $amount);
    }

    /**
     * Add month
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addMonth($amount)
    {
        return $this->date->addField(2, $amount);
    }

    /**
     * Add week
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addWeek($amount)
    {
        return $this->date->addField(3, $amount);
    }

    /**
     * Add day
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addDay($amount)
    {
        return $this->date->addField(5, $amount);
    }

    /**
     * Add hour
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addHour($amount)
    {
        return $this->date->addField(11, $amount);
    }

    /**
     * Add minute
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addMinute($amount)
    {
        return $this->date->addField(12, $amount);
    }

    /**
     * Add second
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addSecond($amount)
    {
        return $this->date->addField(13, $amount);
    }

    /**
     * Add millisecond
     *
     * @param int $amount
     *
     * @return DateTime
     */
    public function addMillisecond($amount)
    {
        return $this->date->addField(14, $amount);
    }
}
