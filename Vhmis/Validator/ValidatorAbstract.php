<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

use Vhmis\Utils\Exception\MissingOptionException;

abstract class ValidatorAbstract implements ValidatorInterface
{

    /**
     * Input value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Filter value for valid input value.
     *
     * @var mixed
     */
    protected $standardValue;

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Thông báo lỗi
     *
     * @var string
     */
    protected $message;

    /**
     * Mã lỗi
     *
     * @var string
     */
    protected $messageCode;

    /**
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Default options.
     *
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * Required options.
     *
     * @var array
     */
    protected $requiredOptions = [];

    /**
     * Default contruct
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Thực thi trực tiếp
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke($value)
    {
        return $this->isValid($value);
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return ValidatorAbstract
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Check missing options.
     *
     * @throws MissingOptionException
     */
    public function checkMissingOptions()
    {
        foreach ($this->requiredOptions as $option) {
            if (!array_key_exists($option, $this->options)) {
                throw new MissingOptionException('Missing option ' . $option . ' for validator');
            }
        }
    }

    /**
     * Reset validator.
     *
     * @return ValidatorAbstract
     */
    public function reset()
    {
        $this->init();

        $this->options = $this->defaultOptions;
        $this->standardValue = null;

        return $this;
    }

    /**
     * Deprecated method, will be remove soon.
     * Using setError.
     */
    protected function setMessage($code)
    {
        $this->setError($code);
    }
    
    /**
     * Set error.
     * 
     * @param string $code
     * 
     * @return ValidatorAbstract
     */
    protected function setError($code)
    {
        $this->message = $this->messages[$code];
        $this->messageCode = $code;
        
        return $this;
    }

    /**
     * Lấy thông báo của kết quả kiểm tra.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Lấy mã thông báo của kết quả kiểm tra.
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Lấy dữ liệu chuẩn.
     *
     * @return mixed
     */
    public function getStandardValue()
    {
        return $this->standardValue;
    }

    /**
     * Init method.
     *
     * Default is empty, nothing setup.
     */
    protected function init()
    {

    }

    /**
     * Set not valid info.
     * Deprecated method, will be remove soon. Using setError.
     *
     * @param string $code
     * @param string $message
     *
     * @return ValidatorAbstract
     */
    protected function setNotValidInfo($code, $message)
    {
        $this->message = $message;
        $this->messageCode = $code;

        return $this;
    }

    /**
     * Kiểm tra xem 1 giá trị có hợp lệ theo regex.
     *
     * @param mixed $value
     * @param string $pattern
     *
     * @return boolean
     */
    protected function isValidRegex($value, $pattern)
    {
        if (preg_match($pattern, $value) === 1) {
            return true;
        }

        return false;
    }
}
