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

/**
 * Add helper for DateTime
 */
abstract class AbstractHelper extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Set date object
     *
     * @param DateTime $date
     *
     * @return AbstractHelper
     */
    public function setDateTimeObject($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date object
     *
     * @return DateTime
     */
    public function getDateTimeObject()
    {
        return $this->date;
    }
}
