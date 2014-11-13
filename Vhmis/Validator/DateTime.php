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
    const E_NOT_DATETIME = 'validator_datetime_not_datetime';
    const E_NOT_VALID_OPTION = 'validator_datetime_not_valid_option';

    protected $messages = [
        self::E_NOT_DATETIME => 'The give value is not valid for datetime.',
        self::E_NOT_VALID_OPTION => 'The option is not valid.'
    ];

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['pattern'];

    /**
     *
     * @var \IntlDateFormatter[]
     */
    protected $formatters;

    /**
     * Construct. Using locale options.
     */
    public function __construct()
    {
        $this->options = [
            'locale' => locale_get_default(),
            'timezone' => date_default_timezone_get(),
            'calendar' => 'Gregorian'
        ];

        $this->defaultOptions = $this->options;
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
    public function isValid($value)
    {
        $this->value = $value;
        
        $this->checkMissingOptions();

        $formatter = $this->getFormatter();

        if ($formatter === false) {
            return false;
        }

        $timestamp = $formatter->parse($this->value);

        if ($timestamp === false) {
            $this->setNotValidInfo(self::E_NOT_DATETIME, $this->messages[self::E_NOT_DATETIME]);
            return false;
        }

        $this->standardValue = new \Vhmis\I18n\DateTime\DateTime;
        $this->standardValue->setTimestamp($timestamp);

        return true;
    }

    /**
     * Get datetime formatter for validation.
     *
     * @return \IntlDateFormatter|false
     */
    protected function getFormatter()
    {
        $formatterId = $this->options['locale'] . '@calendar=' . $this->options['calendar'];
        
        if (!isset($this->formatters[$formatterId])) {
            $formater = new \IntlDateFormatter($formatterId, 3, 3);
            
            echo $formater->getLocale(\Locale::VALID_LOCALE);

            if($formater === false) {
                $this->setNotValidInfo(self::E_NOT_VALID_OPTION, $this->messages[self::E_NOT_VALID_OPTION]);
                return false;
            }
            
            $formater->setLenient(false);
            $this->formatters[$formatterId] = $formater;
        }
        
        if($this->formatters[$formatterId]->setTimeZone($this->options['timezone'])) {
            $this->setNotValidInfo(self::E_NOT_VALID_OPTION, $this->messages[self::E_NOT_VALID_OPTION]);
            return false;
        }
        
        $this->formatters[$formatterId]->setPattern($this->options['pattern']);

        return $this->formatters[$formatterId];
    }

    /**
     * Init.
     * 
     * Set default options.
     */
    protected function init()
    {
        $this->defaultOptions = [
            'locale' => locale_get_default(),
            'timezone' => date_default_timezone_get(),
            'calendar' => 'Gregorian'
        ];
    }
}
