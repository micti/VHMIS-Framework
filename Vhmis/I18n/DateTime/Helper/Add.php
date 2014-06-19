<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use \Vhmis\Utils\Std\AbstractDateTimeHelper;
use \Vhmis\I18n\DateTime\DateTime;

class Add extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    protected $params = 1;

    public function addEra($amount)
    {
        return $this->date->addField(0, $amount);
    }

    public function addYear($amount)
    {
        return $this->date->addField(1, $amount);
    }

    public function addMonth($amount)
    {
        return $this->date->addField(2, $amount);
    }

    public function addWeek($amount)
    {
        return $this->date->addField(3, $amount);
    }

    public function addDay($amount)
    {
        return $this->date->addField(5, $amount);
    }

    public function addHour($amount)
    {
        return $this->date->addField(11, $amount);
    }

    public function addMinute($amount)
    {
        return $this->date->addField(12, $amount);
    }

    public function addSecond($amount)
    {
        return $this->date->addField(13, $amount);
    }

    public function addMillisecond($amount)
    {
        return $this->date->addField(14, $amount);
    }
}
