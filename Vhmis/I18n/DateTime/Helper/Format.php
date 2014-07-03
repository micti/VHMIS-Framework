<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

/**
 * DateTime format helper
 */
class Format extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'formatFull'   => 0,
        'formatLong'   => 0,
        'formatMedium' => 0,
        'formatShort'  => 0
    );

    /**
     * Format datetime in full style
     *
     * @return string
     */
    public function formatFull()
    {
        return $this->date->format(0);
    }

    /**
     * Format datetime in long style
     *
     * @return string
     */
    public function formatLong()
    {
        return $this->date->format(1);
    }

    /**
     * Format datetime in medium style
     *
     * @return string
     */
    public function formatMedium()
    {
        return $this->date->format(2);
    }

    /**
     * Format datetime in short style
     *
     * @return string
     */
    public function formatShort()
    {
        return $this->date->format(3);
    }
}
