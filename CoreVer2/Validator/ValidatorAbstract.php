<?php

namespace Vhmis\Validator;

abstract class ValidatorAbstract
{

    const NOTTYPE = "-1";

    const NOTVALID = "-2";

    const NOTRANGE = "-3";

    const NOTEMPTY = "-4";

    const NOTEXIST = "-5";

    const DATENOTTYPE = "-1001";

    const DATENOTVALID = "-1002";

    const DATENOTRANGE = "-1003";

    const ATENOTEMPTY = "-1004";

    const FLOATNOTTYPE = "-1101";

    const FLOATNOTVALID = "-1102";

    const FLOATNOTRANGE = "-1103";

    const FLOATNOTEMPTY = "-1104";

    const INTNOTTYPE = "-1201";

    const INTNOTVALID = "-1202";

    const INTNOTRANGE = "-1203";

    const INTNOTEMPTY = "-1204";

    const NUMBERNOTTYPE = "-1301";

    const NUMBERNOTVALID = "-1302";

    const NUMBERNOTRANGE = "-1303";

    const NUMBERNOTEMPTY = "-1304";

    protected $_message;

    protected $_messageCode;

    protected $_messageTranslatorCode;

    protected $_value;

    /**
     * Dữ liệu chuẩn của dữ liệu được kiểm tra,
     * được thiết lập sau khi kiểm tra hợp lệ
     *
     * @var mixed
     */
    protected $_standardValue;

    /**
     * Khởi tạo
     */
    public function __construct($options = null)
    {}

    /**
     * Phương thức abstract dùng để kiểm tra dữ liệu
     */
    abstract function isValid($value, $options = null);

    /**
     * Lấy nội dung của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Lấy mã của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessageCode()
    {
        return $this->_messageCode;
    }

    /**
     * Lấy mã chuyển đổi sang ngôn ngữ khác của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessageTranslatorCode()
    {
        return $this->_messageTranslatorCode;
    }

    /**
     * Lấy toàn bộ thông tin của kết quả kiểm tra
     *
     * @return array
     */
    public function getMessages()
    {
        return array(
            'message' => $this->_message,
            'code' => $this->_messageCode,
            'translator' => $this->_messageTranslatorCode
        );
    }

    /**
     * Lấy dữ liệu chuẩn
     *
     * @return mixed
     */
    public function getStandardValue()
    {
        return $this->_standardValue;
    }

    /**
     * Thiết lập thông tin về kết quả kiểm tra
     *
     * @param string $message Nôi dung
     * @param string $code Mã nội dung
     * @param string $translatorCode Mã nội dung dùng để chuyển đổi sang ngôn ngữ khác
     */
    protected function _setMessage($message, $code, $translatorCode)
    {
        $this->_message = $message;
        $this->_messageCode = $code;
        $this->_messageTranslatorCode = $translatorCode;
    }

    /**
     * Kiểm tra xem 1 giá trị có hợp lệ theo regex
     *
     * @param type $value Giá trị cần kiểm tra
     * @param type $pattern Regex pattern sử dụng để kiểm tra
     * @return boolean
     */
    protected function _isValidRegex($value, $pattern)
    {
        if (preg_match($pattern, $value)) {
            return true;
        } else {
            return false;
        }
    }
}