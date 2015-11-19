<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

use Vhmis\Db\ModelInterface;

/**
 * DbExist validator.
 */
class DbExists extends ValidatorAbstract
{
    /**
     * Error code : Model isn't an instance of ModelInterface
     */
    const E_NO_MODEL = 'validator_dbexists_no_model';

    /**
     * Error code : Value doesn't exist in db
     */
    const E_NO_EXISTS = 'validator_dbexists_no_exists';

    /**
     * Error code : Database error
     */
    const E_DB_ERROR = 'validator_dbexists_db_error';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::E_NO_MODEL  => 'Model isn\'t an instance of ModelInterface.',
        self::E_NO_EXISTS => 'Value doesn\'t exist in db.',
        self::E_DB_ERROR  => 'Database Error',
    ];

    /**
     * Required options
     *
     * @var array
     */
    protected $requiredOptions = ['model'];

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
        $model = $this->options['model'];

        if (!($model instanceof ModelInterface)) {
            $this->setError(self::E_NO_MODEL);
            return false;
        }

        try {
            $result = $this->options['model']->findOne([
                [$this->options['field'], '=', $value]
            ]);

            if ($result === null) {
                $this->setError(self::E_NO_EXISTS);
                return fasle;
            }

            $this->standardValue = $result;

            return true;
        } catch (\Exception $ex) {
            $this->setError(self::E_DB_ERROR);
            return false;
        }
    }

    /**
     * Init.
     *
     * Set default options.
     */
    protected function init()
    {
        $this->defaultOptions = [
            'field' => 'id'
        ];
    }
}
