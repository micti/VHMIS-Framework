<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

/**
 * DateTime validator.
 */
class DateTime extends ValidatorAbstract
{
    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['format'];

    /**
     * Construct. Using locale options.
     */
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->useLocaleOptions();
    }

    /**
     * Reset validator. Using locale options.
     *
     * @return Int
     */
    public function reset()
    {
        parent::reset();

        $this->init();

        return $this;
    }

    public function isValid()
    {
        $this->checkMissingOptions();

        $formatter = $this->getFormatter();
    }

    /**
     * Get datetime formatter for validation.
     *
     * @return \IntlDateFormatter
     */
    protected function getFormatter()
    {
        $locale = $this->options['locale'];
        $formater = new \IntlDateFormatter($locale, 3, 3);
        $formater->setPattern($this->options['locale']);

        return $formater;
    }
}
