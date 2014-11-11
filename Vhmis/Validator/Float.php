<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

use \NumberFormatter;

/**
 * Float validator.
 */
class Float extends NumberAbstract
{

    /**
     * Error code : Not valid type.
     */
    const E_NOT_TYPE = 'validator_float_not_valid_type';

    /**
     * Error code : Not integer.
     */
    const E_NOT_FLOAT = 'validator_float_not_float';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_TYPE => 'The given value is not a valid type.',
        self::E_NOT_FLOAT => 'The given value is not a float number.'
    );

    /**
     * Validate
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

        if (is_int($value) || is_float($value)) {
            $this->standardValue = (float) $value;
            return true;
        }

        if (!$this->isNumber('float', $value)) {
            $this->setNotValidInfo(self::E_NOT_FLOAT, $this->messages[self::E_NOT_FLOAT]);
            return false;
        }

        return true;
    }
}
