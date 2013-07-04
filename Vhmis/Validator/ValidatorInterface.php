<?php

namespace Vhmis\Validator;

interface ValidatorInterface
{
    /**
     * Phương thức dùng để kiểm tra dữ liệu
     *
     * @return boolean
     */
    public function isValid($value);

    /**
     * Dùng để lấy thông báo lỗi
     *
     * @return string
     */
    public function getMessage();

    /**
     * Dùng để lấy code lỗi
     *
     * @return string
     */
    public function getMessageCode();
}

