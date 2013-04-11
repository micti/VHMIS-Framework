<?php
use Vhmis\Config\Configure;

/**
 * Auth
 *
 * Thực hiện quá trình kiểm tra, thiết lập và lấy thông tin đăng nhập, đăng xuất
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem
 * file thông tin đi kèm
 *
 * @copyright Copyright 2011, IT Center, Viethan IT College
 *            (http://viethanit.edu.vn)
 * @link https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category VHMIS
 * @package Components
 * @subpackage Auth
 * @since 1.0.0
 * @license All rights reversed
 */
class Vhmis_Component_Auth extends Vhmis_Component
{

    protected $_dbUser;

    protected $_user;

    protected $_session;

    protected $_appSecretKey;

    public function init()
    {
        // Kết nối CSDL
        $db = $this->_db('System');
        
        $config = Configure::get('Config');
        $this->_appSecretKey = $config['security']['secret'];
        
        $this->_dbUser = new Vhmis_Model_System_User(array(
            'db' => $db
        ));
        
        // Session
        Zend_Session::start();
        $this->_session = new Zend_Session_Namespace('Auth');
        
        // Thông tin người dùng
        $this->_user = $this->_findUserInfo();
    }

    /**
     * Kiểm tra người dùng đã đăng nhập hay chưa, xem thêm phương thức getUser
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        if ($this->_user === null)
            return false;
        return true;
    }

    /**
     * Lấy đối tượng Db Row của người dùng, dùng để cập nhật, chỉnh sửa
     */
    public function user()
    {
        return $this->_user;
    }

    /**
     * Lấy thông tin của người dùng
     *
     * @return array Mảng chứa thông tin người dùng
     */
    public function getUser()
    {
        if ($this->_user === null)
            return null;
            
            // Mảng người dùng
        $user = $this->_dbUser->toArray($this->_user);
        
        // TODO : có nên xóa dữ liệu nhạy cảm như password ...
        
        return $user;
    }

    /**
     * Thực hiện đăng nhập
     *
     * @param string $username
     *            Tên người dùng
     * @param string $password
     *            Mật khẩu người dùng
     * @return int 0 : Đăng nhập ko thành công, 1 : Đăng nhập thành công lần đầu
     *         qua webmail (có khởi tạo tài khoản), 2 Đăng nhập thành công
     */
    public function login($username, $password)
    {
        $user = $this->_dbUser->getUserByUsername($username);
        
        // Kiểm tra nội bộ
        $ok = false;
        if ($user != null) {
            $passwordHash = Vhmis_Utility_String::hash($password, $user->password_salt, $this->_appSecretKey);
            if ($passwordHash == $user->password) {
                $this->_session->username = $username;
                $this->_session->password = $passwordHash;
                return 2;
            }
        } else {
            return 0;
        }
        
        // Kiểm tra qua webmail
        if ($ok == false) {
            if ($this->_webmailLogin($username, $password) != false) {
                $passwordSalt = Vhmis_Utility_String::random('alnum', 20);
                $password = Vhmis_Utility_String::hash($password, $passwordSalt, $this->_appSecretKey);
                
                $this->_session->username = $username;
                $this->_session->password = $password;
                
                if ($user != null) {
                    // Update trong hệ thống
                    $user->password_salt = $passwordSalt;
                    $user->password = $password;
                    $user->save();
                    return 1;
                }
            }
        }
        
        return 0;
    }

    /**
     * Thực hiện đăng xuất
     */
    public function logout()
    {
        $this->_session->username = null;
        $this->_session->password = null;
    }

    /**
     * Lấy thông tin của người dùng
     *
     * @return mixed Nếu không có thì null, nếu có thì thông tin người dùng nằm
     *         trong đối tượng Row của Vhmis_Model_System_User
     */
    public function _findUserInfo()
    {
        if (!$this->_session->username || $this->_session->username === null)
            return null;
        if (!$this->_session->password || $this->_session->password === null)
            return null;
        
        return $this->_dbUser->getUserByLogin($this->_session->username, $this->_session->password);
    }

    /**
     * Login qua Webmail
     *
     * @param string $user
     *            Username
     * @param string $pass
     *            Password, không mã hóa
     * @return boolean Kết quả
     */
    protected function _webmailLogin($user, $pass)
    {
        $request = new Vhmis_Network_Http_Curl();
        $request->setRequestInfo('http://mail.viethanit.edu.vn/zmail/jsp/Login.jsp', 'POST', 
            'http://mail.viethanit.edu.vn/zmail/jsp/LoginF.jsp?language=en', 
            'language_code=en&domain_idx=0&member_id=' . $user . '&password=' . $pass);
        $requestResult = $request->sendSimpleRequest();
        
        if (strpos($requestResult, 'Login Check Error') === false) {
            return true;
        } else {
            return false;
        }
    }
}