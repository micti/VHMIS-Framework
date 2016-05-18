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
 * Slug validator.
 */
class Slug extends ValidatorAbstract
{

    /**
     * Error code : Not valid type.
     */
    const E_NOT_TYPE = 'validator_slug_not_valid_type';

    /**
     * Error code : Not integer.
     */
    const E_NOT_SLUG = 'validator_slug_not_float';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_TYPE => 'The given value is not a valid type.',
        self::E_NOT_SLUG => 'The given value is not a slug format.'
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
        $this->value = $this->standardValue = $value;

        if (!is_string($value)) {
            $this->setNotValidInfo(self::E_NOT_TYPE, $this->messages[self::E_NOT_TYPE]);
            return false;
        }

        if (!$this->isValidRegex($value, '/^[a-z0-9-]+$/')) {
            $this->setNotValidInfo(self::E_NOT_SLUG, $this->messages[self::E_NOT_SLUG]);
            return false;
        }

        return true;
    }
}
