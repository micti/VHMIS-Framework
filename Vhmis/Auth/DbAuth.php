<?php

namespace Vhmis\Auth;

class DbAuth implements AuthInterface, CheckCredentialAwareInterface
{
    /**
     *
     * @var \Vhmis\Db\ModelInterface
     */
    protected $model;

    /**
     *
     * @var \Vhmis\Db\EntityInterface
     */
    protected $identityInfo;

    /**
     * Trường nhận dạng
     *
     * @var string
     */
    protected $identityField = 'username';

    /**
     *
     * @var \Vhmis\Auth\CheckCredentialInterface
     */
    protected $checkCredential;

    public function __construct()
    {

    }

    /**
     * Bảng dữ liệu chứa thông tin đăng nhập
     *
     * @param \Vhmis\Db\ModelInterface $model
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Thông tin trường nhận dạng (username or email ...)
     *
     * @param string $identity;
     */
    public function setIdentityField($identityField)
    {
        $this->identityField = $identityField;
        return $this;
    }

    /**
     * Chứng thực
     *
     * @param string $identity
     * @param string $credential
     * @return int
     */
    public function auth($identity, $credential)
    {
        $this->identityInfo = array();

        $user = $this->getIdentity($identity);

        if ($user === null) {
            return DbAuth::AUTH_NOT_FOUND_IDENTITY;
        }

        if ($this->checkCredential->check($user, $credential) === false) {
            return DbAuth::AUTH_NOT_MATCH_CREDENTIAL;
        }

        $this->identityInfo = $user->toArray();

        return DbAuth::AUTH_OK;
    }

    /**
     * Lấy đối tượng được nhận dạng trong database
     *
     * @param string $identity
     * @return \Vhmis\Db\EntityInterface
     */
    public function getIdentity($identity)
    {
        $user = $this->model->findOne(array(array($this->identityField, 'like', $identity)));

        return $user;
    }

    /**
     * Lấy thông tin nhận dạng
     *
     * @return array
     */
    public function getIdentityInfo()
    {
        return $this->identityInfo;
    }

    /**
     * Thiết lập Check Credential
     *
     * @param \Vhmis\Auth\CheckCredentialInterface $checkCredential
     * @return \Vhmis\Auth\DbAuth
     */
    public function setCheckCredential(CheckCredentialInterface $checkCredential)
    {
        $this->checkCredential = $checkCredential;

        return $this;
    }

    /**
     * Lấy Check Credential
     *
     * @return \Vhmis\Auth\CheckCredentialInterface
     */
    public function getCheckCredential()
    {
        return $this->checkCredential;
    }
}
