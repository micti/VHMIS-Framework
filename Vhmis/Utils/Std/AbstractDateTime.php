<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils\Std;

/**
 * DateTime abstract
 */
abstract class AbstractDateTime
{
    /**
     * Calendar type
     *
     * @var string
     */
    protected $calendarType = 'gregorian';

    /**
     * Helpers
     *
     * @var AbstractDateTimeHelper[]
     */
    protected $helpers = array();

    /**
     * Helper namespace
     *
     * @var AbstractDateTimeHelper[]
     */
    protected $helperNamespace = '';

    /**
     * Helper list
     *
     * @var array
     */
    protected $helperList = array();

    /**
     * Get calendar type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Get helper
     *
     * @param string $name
     *
     * @return AbstractDateTimeHelper
     */
    public function getHelper($name)
    {
        if (!isset($this->helperList[$name])) {
            return null;
        }

        if (!isset($this->helpers[$name])) {
            $helperClass = $this->helperNamespace . '\\' . $this->helperList[$name];
            $this->helpers[$name] = new $helperClass;
            $this->helpers[$name]->setDateTimeObject($this);
        }

        return $this->helpers[$name];
    }
}
