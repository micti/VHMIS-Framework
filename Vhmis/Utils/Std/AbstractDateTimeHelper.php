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
class AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTimeInterface
     */
    protected $date;
    
    protected $params = 0;

    public function __invoke($name, $arguments)
    {
        if (!is_array($arguments)) {
            return null;
        }

        if (count($arguments) !== $this->params) {
            return null;
        }

        if (method_exists($this, $name)) {
            return call_user_func_array(array($this, $name), $arguments);
        }

        return null;
    }

    /**
     * Set date object
     *
     * @param DateTimeInterface $date
     *
     * @return \Vhmis\Utils\Std\AbstractDateTimeHelper
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date object
     *
     * @return DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }
}
