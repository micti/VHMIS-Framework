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
 * DateTime Helper abstract
 */
abstract class AbstractDateTimeHelper
{
    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array();

    /**
     * Object callable
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __invoke($name, $arguments)
    {
        if (!is_array($arguments)) {
            return null;
        }

        if (!isset($this->methodList[$name])) {
            return null;
        }

        if (count($arguments) !== $this->methodList[$name]) {
            return null;
        }

        return call_user_func_array(array($this, $name), $arguments);
    }

    /**
     * Set date object
     *
     * @param DateTimeInterface $date
     *
     * @return \Vhmis\Utils\Std\AbstractDateTimeHelper
     */
    abstract public function setDateTimeObject($date);

    /**
     * Get date object
     *
     * @return DateTimeInterface
     */
    abstract public function getDateTimeObject();
}
