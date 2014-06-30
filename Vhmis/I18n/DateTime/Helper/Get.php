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
    * Method list and param number
    *
    * @var Array
    */
    protected $methodList = array(
        'getDate'                     => 1,
        'getDateWithExtendedYear'     => 1,
        'getDateWithRelatedYear'      => 1,
        'getTime'                     => 1,
        'getDateTime'                 => 1,
        'getDateTimeWithExtendedYear' => 1,
        'getDateTimeWithRelatedYear'  => 1,
        'getMillisecond'              => 1,
        'getSecond'                   => 1,
        'getMinute'                   => 1,
        'getHour'                     => 1,
        'getDay'                      => 1,
        'getIsLeapMonth'              => 1,
        'getMonth'                    => 1,
        'getYear'                     => 1,
        'getExtendedYear'             => 1,
        'getEra'                      => 1
    );

    /**
     * Get date (based on ISO format yyyy-mm-dd)
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date->format('yyyy-MM-dd');
    }

    /**
     * Get date with extended year (based on ISO format yyyy-mm-dd)
     *
     * @return string
     */
    public function getDateWithExtendedYear()
    {
        return $this->date->format('YYYY-MM-dd');
    }

    /**
     * Get date with related year (based on ISO format yyyy-mm-dd)
     *
     * @return string
     */
    public function getDateWithRelatedYear()
    {
        return $this->date->format('rrrr-MM-dd');
    }

    /**
     * Get time (based on ISO format hh:mm:ss)
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

    /**
     * Get date and time with extended year (based on ISO format yyyy-mm-dd hh:mm:ss)
     *
     * @return string
     */
    public function getDateTimeWithExtendedYear()
    {
        return $this->date->format('YYYY-MM-dd HH:mm:ss');
    }

    /**
     * Get date and time with related year (based on ISO format yyyy-mm-dd hh:mm:ss)
     *
     * @return string
     */
    public function getDateTimeWithRelatedYear()
    {
        return $this->date->format('rrrr-MM-dd HH:mm:ss');
    }

    /**
     * Get millisecond
     *
     * @return int
     */
    public function getMillisecond()
    {
        return $this->date->getField(14);
    }

    /**
     * Get second
     *
     * @return int
     */
    public function getSecond()
    {
        return $this->date->getField(13);
    }

    /**
     * Get minute
     *
     * @return int
     */
    public function getMinute()
    {
        return $this->date->getField(12);
    }

    /**
     * Get hour
     *
     * @return int
     */
    public function getHour()
    {
        return $this->date->getField(11);
    }

    /**
     * Get day
     *
     * @return int
     */
    public function getDay()
    {
        return $this->date->getField(5);
    }

    /**
     * Get is leap month
     *
     * @return int
     */
    public function getIsLeapMonth()
    {
        return $this->date->getField(22);
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->date->getField(2);
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->date->getField(1);
    }

    /**
     * Get extended year
     *
     * @return int
     */
    public function getExtendedYear()
    {
        return $this->date->getField(19);
    }

    /**
     * Get era
     *
     * @return int
     */
    public function getEra()
    {
        return $this->date->getField(0);
    }
}
