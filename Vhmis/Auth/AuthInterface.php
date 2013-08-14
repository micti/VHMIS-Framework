<?php

namespace Vhmis\Auth;

interface AuthInterface
{
    const AUTH_NOT_FOUND_IDENTITY = 0;
    const AUTH_NOT_MATCH_CREDENTIAL = 1;
    const AUTH_OK = 2;

    /**
     * Chứng thực
     *
     * @param mixed $identity
     * @param mixed $credential
     */
    public function auth($identity, $credential);

    /**
     * Lấy thông tin của Indentity trong trường hợp chứng thực đúng
     */
    public function getIdentityInfo();
}

