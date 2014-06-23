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

class Set extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    protected $params = 1;

    public function setSecond($second)
    {
        $this->date->setField(13, $second);

        return $this->date;
    }

    public function setMinute($minute)
    {
        $this->date->setField(12, $minute);

        return $this->date;
    }

    public function setHour($hour)
    {
        $this->date->setField(11, $hour);

        return $this->date;
    }

    public function setDay($day)
    {
        $month = $this->date->getField(2);
        $year = $this->date->getField(1);

        $this->date->setField(5, $day);

        return $this->fix($year, $month);
    }

    public function setIsLeapMonth($leap)
    {
        $this->date->setField(22, $leap);

        return $this->date;
    }

    public function setMonth($month)
    {
        $year = $this->date->getField(1);

        if (!$this->date->setField(2, $month)) {
            return $this->date;
        }

        return $this->fix($year, $month);
    }

    public function setLeapMonth($month)
    {
        $year = $this->date->getField(1);
        $currentMonth = $this->date->getField(2);
        $day = $this->date->getField(5);
        $isLeap = $this->date->getField(22);

        $this->setMonth($month);
        $this->date->addField(2, 1);

        if ($this->date->getField(22) !== 1) {
            $this->setYear($year);
            $this->setMonth($currentMonth);
            $this->setDay($day);
            $this->setIsLeapMonth($isLeap);
        }

        // fix day
        // $day = $this->date->getActualMaximumValueOfField(5);
        return $this->date;
    }

    public function setYear($year)
    {
        $month = $this->date->getField(2);

        if (!$this->date->setField(1, $year)) {
            return $this->date;
        }

        return $this->fix($year, $month);
    }

    public function setEra($era)
    {
        $month = $this->date->getField(2);
        $year = $this->date->getField(1);

        $this->date->setField(0, $era);

        return $this->fix($year, $month);
    }

    protected function fix($year, $month)
    {
        $this->date->setField(22, 0);

        if ($month !== $this->date->getField(2)) {
            $this->date->setField(5, 1); // move first day
            $this->date->setField(1, $year);
            $this->date->setField(2, $month);

            $max = $this->date->getMaximumValueOfField(5);
            $this->date->setField(5, $max['actual']);
        }

        return $this->date;
    }
}
