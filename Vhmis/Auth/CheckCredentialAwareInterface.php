<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Vhmis\Auth;

interface CheckCredentialAwareInterface
{
    /**
     * Thiết lập Check Credential
     *
     * @param \Vhmis\Auth\CheckCredentialInterface $checkCredential
     */
    public function setCheckCredential(CheckCredentialInterface $checkCredential);

    /**
     * Lấy Check Credential
     *
     * @return \Vhmis\Auth\CheckCredentialInterface
     */
    public function getCheckCredential();
}
