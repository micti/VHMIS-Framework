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

    protected $params = 0;

    /**
     * Get date (based on ISO format yyyy-mm-dd)
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date->format('yyyy-MM-dd');
    }

    public function getDateWithExtendedYear()
    {
        return $this->date->format('YYYY-MM-dd');
    }

    /**
     * Get date (based on ISO format hh:mm:ss)
     *
     * @return string
     */
    public function getTime()
    {
        return $this->date->format('HH:mm:ss');
    }

    /**
     * Get date and time (based on ISO format yyyy-mm-dd hh:mm:ss)
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->date->format('yyyy-MM-dd HH:mm:ss');
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
