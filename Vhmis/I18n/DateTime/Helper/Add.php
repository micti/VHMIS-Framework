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

    public function __invoke($name, $arguments)
    {
        $name = str_replace('add', '', $name);
        $name = strtolower($name);

        if(count($arguments) !== 1) {
            return null;
        }

        if(method_exists($this, $name)) {
            return $this->$name($arguments[0]);
        }

        return null;
    }

    public function era($amount)
    {
        return $this->date->addField(0, $amount);
    }
    
    public function year($amount)
    {
        return $this->date->addField(1, $amount);
    }

    public function month($amount)
    {
        return $this->date->addField(2, $amount);
    }

    public function week($amount)
    {
        return $this->date->addField(3, $amount);
    }

    public function day($amount)
    {
        return $this->date->addField(5, $amount);
    }

    public function hour($amount)
    {
        return $this->date->addField(11, $amount);
    }

    public function minute($amount)
    {
        return $this->date->addField(12, $amount);
    }

    public function second($amount)
    {
        return $this->date->addField(13, $amount);
    }

    public function millisecond($amount)
    {
        return $this->date->addField(14, $amount);
    }
}