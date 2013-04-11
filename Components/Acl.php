<?php

/**
 * Acl
 *
 * Xử lý chứng thực quyền hạn của user, group
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category VHMIS
 * @package Core
 * @subpackage Security
 * @since 1.0.0
 * @license All rights reversed
 */
use Vhmis\Config\Configure;

/**
 */
class Vhmis_Component_Acl extends Vhmis_Component
{

    public $_acl = array();

    protected $_apps = array();

    protected $_groups = array();

    protected $_departments = array();

    protected $_users = array();

    protected $_dbAcl;

    public function init()
    {
        // Kết nối CSDL
        $this->_db('System');
        $db = Configure::get('DbSystem');
        $this->_dbAcl = new Vhmis_Model_System_Acl_Permission(array(
            'db' => $db
        ));
    }

    /**
     * Thêm app vào danh sách
     */
    public function addApp($app)
    {
        if (!isset($this->_apps[$app]))
            $this->_apps[$app] = array();
    }

    /**
     * Thêm resource vào danh sách
     */
    public function addResource($app, $resource)
    {
        $this->addApp($app);
        if (!isset($this->_apps[$app][$resource]))
            $this->_apps[$app][$resource] = array();
    }

    public function addGroup($group)
    {
        if (in_array($group, $this->_groups))
            return;
        
        $this->_groups[] = $group;
    }

    public function addDepartment($department)
    {
        if (in_array($department, $this->_departments))
            return;
        
        $this->_departments[] = $department;
    }

    public function addUser($user)
    {
        if (in_array($user, $this->_users))
            return;
        
        $this->_users[] = $user;
    }

    /**
     * Hàm khởi tạo, load các quyền ứng với resource, user, group đã được khai
     * báo
     * Trong cùng một request, khi có sự thay đổi (thêm resource, user, group),
     * hàm cần được gọi lại
     */
    public function build()
    {
        $permissions = $this->_dbAcl->getAcl($this->_apps, $this->_groups, $this->_users, $this->_departments);
        
        $this->_acl = Vhmis_Component_Acl::makeAclFromDbResult($permissions);
    }

    /**
     * Kiểm tra quyền của user với một hành động nào đó
     *
     * @param array $user Thông tin người dùng
     * @param string $app App
     * @param string $resource Tài nguyên
     * @param string $action Hành động
     * @return bool
     */
    public function isAllow($user, $app, $action, $resource)
    {
        // Kiểm tra quyền cá nhân với trực tiếp hành động
        if (isset($this->_acl[$app][$resource][$action]['u_' . $user['id']]) &&
             $this->_acl[$app][$resource][$action]['u_' . $user['id']] < 2)
            return $this->_acl[$app][$resource][$action]['u_' . $user['id']];
            
            // Kiểm tra quyền cá nhân với hành động đặc biệt '_all'
        if (isset($this->_acl[$app][$resource]['_all']['u_' . $user['id']]) &&
             $this->_acl[$app][$resource]['_all']['u_' . $user['id']] < 2)
            return $this->_acl[$app][$resource]['_all']['u_' . $user['id']];
            
            // Kiểm tra quyền của group
        if ($user['groups'] !== null) {
            foreach ($user['groups'] as $group) {
                // Với trực tiếp hành động
                if (isset($this->_acl[$app][$resource][$action]['g_' . $group]) &&
                     $this->_acl[$app][$resource][$action]['g_' . $group] == 1)
                    return 1;
                    
                    // Với hành động đặc biệt '_all'
                if (isset($this->_acl[$app][$resource]['_all']['g_' . $group]) &&
                     $this->_acl[$app][$resource]['_all']['g_' . $group] == 1)
                    return 1;
            }
        }
        
        // Kiểm tra quyền của phòng ban với trực tiếp hành động
        if (isset($this->_acl[$app][$resource][$action]['d_' . $user['hrm_id_department']]) && $this->_acl[$app][$resource][$action]['d_' .
             $user['hrm_id_department']] == 1)
            return 1;
            
            // Với hành động đặc biệt '_all'
        if (isset($this->_acl[$app][$resource]['_all']['d_' . $user['hrm_id_department']]) && $this->_acl[$app][$resource]['_all']['d_' .
             $user['hrm_id_department']] == 1)
            return 1;
            
            // Không có thông tin ở group hoặc toàn bộ quyền ở group là không
            // cho phép
        return 0;
    }

    public static function makeAclFromDbResult($permissions)
    {
        $acl = array();
        
        if ($permissions !== null) {
            foreach ($permissions as $permission) {
                if ($permission->group_id != 0)
                    $acl[$permission->app][$permission->resource][$permission->action]['g_' . $permission->group_id] = $permission->privileges;
                
                if ($permission->user_id != 0)
                    $acl[$permission->app][$permission->resource][$permission->action]['u_' . $permission->user_id] = $permission->privileges;
                
                if ($permission->department_id != 0)
                    $acl[$permission->app][$permission->resource][$permission->action]['d_' . $permission->department_id] = $permission->privileges;
            }
        }
        
        return $acl;
    }
}