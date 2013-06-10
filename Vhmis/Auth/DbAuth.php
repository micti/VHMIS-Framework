<?php

namespace Vhmis\Auth;

class DbAuth implements AuthInterface
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
    protected $identity = 'username';

    /**
     * Trường chứng thực
     *
     * @var string
     */
    protected $credential = 'password';

    /**
     *
     * @var \Closure
     */
    protected $checkCredential;

    public function __construct()
    {}

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
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * Thông tin trường chứng thực (password)
     *
     * @param string $credential
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    public function setCheckCredentialFunction(\Closure $checkCredential)
    {
        $this->checkCredential = $checkCredential;
        return $this;
    }

    /**
     * Chứng thực
     *
     * @return int
     */
    public function auth($identity, $credential, $options)
    {
        if(!($this->checkCredential instanceof \Closure)) {
            throw new \Exception('DbAuth need a method to check credential. Set via DbAuth::setCheckCredentialFunction method');
        }

        $user = $this->model->findOne(array($this->identity => $identity));
        $this->identityInfo = array();

        if($user === null) {
            return DbAuth::AUTH_NOT_FOUND_IDENTITY;
        }

        if($this->checkCredential->__invoke($credential, $user, $options) === DbAuth::AUTH_NOT_MATCH_CREDENTIAL) {
            return DbAuth::AUTH_NOT_MATCH_CREDENTIAL;
        }

        $this->identityInfo = $user->toArray();
        return DbAuth::AUTH_OK;
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
}
