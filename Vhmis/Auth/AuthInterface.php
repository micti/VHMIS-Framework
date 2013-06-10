<?php

namespace Vhmis\Auth;

interface AuthInterface
{
    const AUTH_NOT_FOUND_IDENTITY = 0;
    const AUTH_NOT_MATCH_CREDENTIAL = 1;
    const AUTH_OK = 2;

    /**
     * Thiết lập model để truy vấn thông tin đăng nhập
     *
     * @param mixed $model
     */
    public function setModel($model);

    /**
     * Thiết lập thông tin để nhận dạng
     *
     * @param mixed $identity
     */
    public function setIdentity($identity);

    /**
     * Thông tin trường để chứng thực
     */
    public function setCredential($credential);

    /**
     * Chứng thực
     *
     * @param mixed $identity
     * @param mixed $credential
     */
    public function auth($identity, $credential, $options);

    /**
     * Lấy thông tin của Indentity trong trường hợp chứng thực đúng
     */
    public function getIdentityInfo();
}

