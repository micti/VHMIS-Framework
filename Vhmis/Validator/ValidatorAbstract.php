<?php

namespace Vhmis\Validator;

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

    public function __invoke($value)
    {
        return $this->isValid($value);
    }

    public function getMessage()
    {
        return '';
    }

    /**
     * Lấy mã của kết quả kiểm tra
     *
     * @return string
     */
    public function getMessageCode()
    {
        return '';
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