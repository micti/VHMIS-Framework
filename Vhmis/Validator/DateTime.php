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
     * Error code : Not valid for datetime.
     */
    const E_NOT_DATETIME = 'validator_datetime_not_datetime';
    
    /**
     * Error code : Not valid for datetime.
     */
    const E_NOT_VALID_TYPE = 'validator_datetime_not_valid_type';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::E_NOT_DATETIME => 'The give value is not valid for datetime.',
        self::E_NOT_VALID_TYPE => 'The give value is not valid type.'
    ];

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = ['pattern'];

    /**
     * Datetime formatters
     *
     * @var \IntlDateFormatter[]
     */
    protected $formatters;

    /**
     * Validate.
     * 
     * @param mixed $value
     * 
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        $this->checkMissingOptions();

        $formatter = $this->getFormatter();
        
        if(!is_string($value)) {
            $this->setNotValidInfo(self::E_NOT_VALID_TYPE, $this->messages[self::E_NOT_VALID_TYPE]);
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
            $formater->setLenient(false);
            $this->formatters[$formatterId] = $formater;
        }

        $this->formatters[$formatterId]->setTimeZone($this->options['timezone']);
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
