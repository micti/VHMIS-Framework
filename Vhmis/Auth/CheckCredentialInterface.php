<?php

namespace Vhmis\Auth;

interface CheckCredentialInterface
{

    /**
     * Kiểm tra thông tin credential có đúng không encryptd
     *
     * @param mixed $identity
     * @param string $credential
     * @return bool
     */
    public function check($identity, $credential);

    /**
     * Mã hóa credential
     * @param string $credential
     */
    public function hash($credential);
}
