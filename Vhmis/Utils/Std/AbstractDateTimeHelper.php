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
     * @var AbstractDateTime
     */
    protected $date;

    /**
     * Set date object
     *
     * @param AbstractDateTime $date
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
     * @return type
     */
    public function getDate()
    {
        return $this->date;
    }
}
