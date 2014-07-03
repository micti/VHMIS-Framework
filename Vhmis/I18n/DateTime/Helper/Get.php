<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

class Get extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'getDate'                     => 0,
        'getDateWithExtendedYear'     => 0,
        'getDateWithRelatedYear'      => 0,
        'getTime'                     => 0,
        'getDateTime'                 => 0,
        'getDateTimeWithExtendedYear' => 0,
        'getDateTimeWithRelatedYear'  => 0,
        'getMillisecond'              => 0,
        'getSecond'                   => 0,
        'getMinute'                   => 0,
        'getHour'                     => 0,
        'getDay'                      => 0,
        'getIsLeapMonth'              => 0,
        'getMonth'                    => 0,
        'getYear'                     => 0,
        'getExtendedYear'             => 0,
        'getEra'                      => 0
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
