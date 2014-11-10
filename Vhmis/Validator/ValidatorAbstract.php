<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

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
     * Use locale in options.
     * 
     * @return ValidatorAbstract
     */
    public function useLocaleOptions()
    {
        $locale = locale_get_default();
        
        $this->options += ['locale' => $locale];
        $this->defaultOptions += ['locale' => $locale];
        
        return $this;
    }

    /**
     * Reset validator.
     * 
     * @return ValidatorAbstract
     */
    public function reset()
    {
        $this->options = $this->defaultOptions;

        return $this;
    }

    /**
     * Thiết lập thông báo.
     *
     * @param type $message Thông báo
     * @param type $code Mã thông báo
     */
    protected function setMessage($code)
    {
        $this->message = $this->messages[$code];
        $this->messageCode = $code;
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
     * Set not valid info.
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
        if (preg_match($pattern, $value)) {
            return true;
        }

        return false;
    }
}
