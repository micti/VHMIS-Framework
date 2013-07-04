<?php

namespace Vhmis\Validator;

use \Vhmis\I18n\Translator;

abstract class ValidatorAbstract implements ValidatorInterface
{
    /**
     * Dữ liệu được đưa vào
     *
     * @var mixed
     */
    protected $value;

    /**
     * Dữ liệu chuẩn của dữ liệu được kiểm tra,
     * được thiết lập sau khi kiểm tra hợp lệ
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
     * Translator
     *
     * @var \Vhmis\I18n\Translator\Translator
     */
    protected $translator;

    /**
     * Thực thi trực tiếp
     *
     * @param mixed $value
     * @return bool
     */
    public function __invoke($value)
    {
        return $this->isValid($value);
    }

    public function setTranslator($translator)
    {
        if($translator instanceof Translator\Translator) {
            $this->translator = $translator;
        }
    }

    /**
     * Thiết lập thông báo
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
     * Lấy thông báo của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Lấy mã thông báo của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->messageCode;
    }

    /**
     * Lấy dữ liệu chuẩn
     *
     * @return mixed
     */
    public function getStandardValue()
    {
        return $this->standardValue;
    }

    /**
     * Kiểm tra xem 1 giá trị có hợp lệ theo regex
     *
     * @param type $value Giá trị cần kiểm tra
     * @param type $pattern Regex pattern sử dụng để kiểm tra
     * @return boolean
     */
    protected function isValidRegex($value, $pattern)
    {
        if (preg_match($pattern, $value)) {
            return true;
        } else {
            return false;
        }
    }
}