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

class Add extends AbstractDateTimeHelper
{
    /**
     * Date object
     *
     * @var DateTime
     */
    protected $date;

    public function era($amount)
    {
        $this->date->addField(0, $amount);

        return $this;
    }
    
    public function year($amount)
    {
        $this->date->addField(1, $amount);

        return $this;
    }

    public function month($amount)
    {
        $this->date->addField(2, $amount);

        return $this;
    }

    public function day($amount)
    {
        $this->date->addField(5, $amount);

        return $this;
    }

    public function hour($amount)
    {
        $this->date->addField(11, $amount);

        return $this;
    }

    public function minute($amount)
    {
        $this->date->addField(12, $amount);

        return $this;
    }

    public function second($amount)
    {
        $this->date->addField(13, $amount);

        return $this;
    }

    public function millisecond($amount)
    {
        $this->date->addField(13, $amount);

        return $this;
    }
}