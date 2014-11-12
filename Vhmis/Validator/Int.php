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
 * Integer validator.
 */
class Int extends NumberAbstract
{
    /**
     * Error code : Not valid type.
     */
    const E_NOT_TYPE = 'validator_int_not_valid_type';

    /**
     * Error code : Not integer.
     */
    const E_NOT_INT = 'validator_int_not_int';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_TYPE => 'The given value is not a valid type.',
        self::E_NOT_INT => 'The given value is not an integer number.'
    );

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

        if (!$this->isValidType($value)) {
            $this->setNotValidInfo(self::E_NOT_TYPE, $this->messages[self::E_NOT_TYPE]);
            return false;
        }

        if (is_int($value)) {
            $this->standardValue = $value;
            return true;
        }

        if (!$this->isNumber('integer', $value)) {
            $this->setNotValidInfo(self::E_NOT_INT, $this->messages[self::E_NOT_INT]);
            return false;
        }

        return true;
    }
}
