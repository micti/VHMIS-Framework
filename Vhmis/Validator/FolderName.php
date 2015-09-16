<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

/**
 * Integer validator.
 */
class FolderName extends ValidatorAbstract
{
    /**
     * Error code : Not valid name.
     */
    const E_NOT_VALID_TYPE = 'validator_foldername_not_valid_type';

    /**
     * Error code : Not valid name.
     */
    const E_NOT_VALID_NAME = 'validator_foldername_not_valid_name';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::E_NOT_VALID_TYPE => 'The given value is not a valid type.',
        self::E_NOT_VALID_NAME => 'The given value is not a valid folder name.'
    ];

    /**
     * Validate.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $this->standardValue = $value;

        if (!is_string($value)) {
            $this->setNotValidInfo(self::E_NOT_VALID_TYPE, $this->messages[self::E_NOT_VALID_TYPE]);

            return false;
        }

        if (trim($value) === '') {
            $this->setNotValidInfo(self::E_NOT_VALID_NAME, $this->messages[self::E_NOT_VALID_NAME]);

            return false;
        }

        if ($this->isValidRegex($value, '/[\\?%*:|"<>\.\/]+/')) { ////
            $this->setNotValidInfo(self::E_NOT_VALID_NAME, $this->messages[self::E_NOT_VALID_NAME]);

            return false;
        }

        return true;
    }
}
