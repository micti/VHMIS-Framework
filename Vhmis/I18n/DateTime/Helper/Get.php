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

class Get extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    public function __invoke($name, $arguments)
    {
        if (!is_array($arguments)) {
            return null;
        }

        if (count($arguments) !== 0) {
            return null;
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return null;
    }

    public function getSecond()
    {
        return $this->date->getField(13);
    }

    public function getMinute()
    {
        return $this->date->getField(12);
    }

    public function getHour()
    {
        return $this->date->getField(11);
    }

    public function getDay()
    {
        return $this->date->getField(5);
    }

    public function getIsLeapMonth()
    {
        return $this->date->getField(22);
    }

    public function getMonth()
    {
        return $this->date->getField(2);
    }

    public function getYear()
    {
        return $this->date->getField(1);
    }

    public function getEra()
    {
        return $this->date->getField(0);
    }
}
