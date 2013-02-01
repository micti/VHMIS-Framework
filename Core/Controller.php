<?php

use Vhmis\Config\Configure;

/**
 * Controller
 *
 * Class điều khiển, dựa vào thông tin để gọi đúng theo yêu cầu request
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
 * @package Core
 * @subpackage URI
 * @since 1.0.0
 * @license All rights reversed
 */

/**
 */
class Vhmis_Controller
{

    /**
     * Thông tin Apps và Request (chủ yếu dùng khi chuyển qua đối tượng khác)
     */
    public $appInfo;

    /**
     * Tên App
     */
    public $app;

    /**
     * Tên url cua app (dung de lam dia chi, dat ten bien .
     * ..)
     */
    public $appUrl;

    /**
     * Tên controller
     */
    protected $_controller;

    /**
     * Tên controller
     */
    public $controller;

    /**
     * Tên Action
     */
    protected $_action;

    /**
     * Tên Action
     */
    public $action;

    /**
     * Các thông số đi kèm
     */
    protected $_params;

    /**
     * Các thông số đi kèm
     */
    public $params;

    /**
     * Kiểu xuất ra
     */
    protected $_output;

    /**
     * Kiểu xuất ra
     */
    public $output;

    /**
     * Config
     */
    protected $_config;

    /**
     * Config
     */
    public $config;

    /**
     * Mảng chứa các components cần gọi
     */
    protected $_components = array('Auth', 'Acl', 'Log');

    /**
     * Mảng chứa các đối tượng của components
     *
     * @var Vhmis_Collection_Components
     */
    public $components;

    /**
     * Mảng chứa các model cần gọi
     */
    protected $_models = array();

    /**
     * Mảng chứa các đối tượng model
     *
     * @var Vhmis_Collection_Models
     */
    public $models;

    /**
     * Đối tượng share collection
     *
     * @var Vhmis_Collection_Shares
     */
    public $shares;

    /**
     * Mảng chứa các Share data cần gọi
     */
    protected $_shares = array();

    /**
     * Yêu cầu login để thực thi controller
     */
    protected $_loginFirst = true;

    /**
     * Yêu cầu login ở một số action nào đó
     */
    protected $_actionLoginFirst = array();

    /**
     * User đang request
     */
    public $user = null;

    /**
     * Nguon tai nguyen va hanh dong len tai nguyen của App
     */
    protected $_resources = null;

    /**
     * Mảng lưu trữ biến truyền cho View
     */
    protected $_data = array();

    /**
     * Ten template dùng trong view
     */
    protected $_viewTemplate = 'Default';

    /**
     * Ten layout dùng trong view
     */
    protected $_viewLayout = 'Default';

    /**
     * Template view
     */
    protected $view;

    /**
     * Khởi tạo
     *
     * @param Vhmis_Network_Request $request
     *            Đối tượng chứa các thông tin của request
     * @param Vhmis_Network_Request $response
     *            Đối tượng thực hiện việc trả kết quả
     */
    public function __construct(Vhmis_Network_Request $request, Vhmis_Network_Response $response)
    {
        $this->config = $this->_config = Configure::get('Config');
        
        $this->request = $request;
        $this->response = $response;
        
        $this->appInfo = $request->app;
        $this->app = $this->appInfo['app'];
        $this->appUrl = $this->appInfo['url'];
        
        $this->action = $this->_action = $this->appInfo['info']['action'];
        $this->params = $this->_params = $this->appInfo['info']['params'];
        $this->output = $this->_output = $this->appInfo['info']['output'];
        $this->controller = $this->_controller = $this->appInfo['info']['controller'];
        
        $this->_resources = isset($this->_config['apps']['info'][$this->appUrl]['resources']) ? $this->_config['apps']['info'][$this->appUrl]['resources'] : null;
        
        $this->models = new Vhmis_Collection_Models();
        $this->shares = new Vhmis_Collection_Shares();
        
        // Gọi các components
        if (is_array($this->_components)) {
            $this->components = new Vhmis_Collection_Components();
            foreach ($this->_components as $comp) {
                $class = 'Vhmis_Component_' . $comp;
                $this->components->load($comp, $this);
            }
        }
        
        if ($this->components->auth !== null) {
            $this->user = $this->components->auth->getUser();
        }
        
        // Kiểm tra login nếu cần thiết
        if ($this->_loginFirst === true || in_array($this->_action, $this->_actionLoginFirst)) {
            if ($this->user === null) {
                if ($this->output != 'html') {
                    $this->set('text', VHMIS_ERROR_LOGINSESSION);
                    $this->set('array', array('error' => 1, 'code' => VHMIS_ERROR_LOGINSESSION, 'message' => 'Login first or Session expired'));
                    $this->view();
                    return;
                }
                
                // Chuyển hướng đến trang login
                $this->redirect($this->_config['site']['path'] . $this->_config['apps']['login-url']);
            }
        }
        
        // ACL
        if ($this->components->acl !== null) {
            if ($this->_resources !== null) {
                foreach ($this->_resources as $resourceName => $resourceInfo) {
                    $this->components->acl->addResource($this->appUrl, $resourceName);
                }
            }
            
            if ($this->user !== null) {
                $this->components->acl->addUser($this->user['id']);
                
                if ($this->user['groups'] != null) {
                    foreach ($this->user['groups'] as $group) {
                        $this->components->acl->addGroup($group);
                    }
                }
                
                // Theo phòng ban
                if (isset($this->user['hrm_id_department']) && $this->user['hrm_id_department'] != 0) {
                    $this->components->acl->addDepartment($this->user['hrm_id_department']);
                }
            }
        }
    }

    /**
     * Thực thi request
     */
    public function init()
    {
        $action = 'action' . $this->_action;
        
        if (method_exists($this, $action)) {
            // Load các model
            $this->_loadModels();
            
            // Load các share
            $this->_loadShares();
            
            $this->_beforeInit();
            $this->$action();
            $this->_afterInit();
        } else {
            echo 'Không tìm thấy action ' . $this->_action . ' . Xây dựng phương thức : ' . $action;
            exit();
        }
    }

    /**
     * Chuyển hướng
     *
     * @param string $url
     *            Địa chỉ cần chuyển hướng
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Thiết lập dữ liệu để truyền sang view
     *
     * @param
     *            string Tên dữ liệu
     * @param
     *            mixed Dữ liệu
     */
    public function set($name, $data)
    {
        $this->_data[$name] = $data;
    }

    /**
     * Gọi view
     */
    public function view($view = '', $layout = '', $template = '')
    {
        if ($layout == '')
            $layout = $this->_viewLayout;
        if ($template == '')
            $template = $this->_viewTemplate;
            
            // Khởi tạo lớp Vhmis_View và thiết lập những thông tin cần thiết
        $this->View = new Vhmis_View();
        $this->View->setViewInfo($view, $layout, $template);
        $this->View->transferViewData($this->_data);
        $this->View->transferControllerData(array('app' => $this->appInfo, 'user' => $this->user));
        $this->View->transferConfigData($this->_config);
        
        // Lấy view
        ob_start();
        $this->View->render();
        $content = ob_get_clean();
        
        // Trả kết quả view thông qua đối tượng response
        $this->response->body($content);
        $this->response->response();
        
        // ?
        exit();
    }

    public function viewMessage($title, $message, $time, $url, $layout = 'Message', $template = '')
    {
        $this->set('title', $title);
        $this->set('message', $message);
        $this->set('time', $time);
        $this->set('url', $url);
        
        $this->view(false, $layout, $template);
    }

    /**
     * Gọi view thông báo lỗi;
     */
    public function viewError($layout = 'Default')
    {
        // Khởi tạo lớp Vhmis_View và thiết lập những thông tin cần thiết
        $this->View = new Vhmis_View();
        $this->View->transferViewData($this->_data);
        $this->View->transferConfigData($this->_config);
        
        // Lấy view
        ob_start();
        $this->View->renderError($layout);
        $content = ob_get_clean();
        
        // Trả kết quả view thông qua đối tượng response
        $this->response->body($content);
        $this->response->response();
        exit();
    }

    /**
     * Gọi view thông báo lỗi kết nối Database;
     *
     * @var string $title Tiêu đề lỗi
     * @var string $message Thông báo
     * @var string $layout Tên layout hiển thị lỗi
     */
    public function viewDbError($title = '', $message = '', $layout = 'Db')
    {
        if ($this->output != 'html') {
            $this->set('text', VHMIS_ERROR_DATABASE);
            $this->set('array', array('error' => 1, 'code' => VHMIS_ERROR_DATABASE, 'message' => 'Db Connection Error'));
            $this->view();
            return;
        }
        
        $this->set('title', 'Kết nối DB bị lỗi');
        $this->set('message', 'Hiện tại kết nối tới CSDL đang gặp lỗi, vui lòng chờ một lát rồi hãy thử lại.');
        
        $this->viewError($layout);
    }

    /**
     * Gọi view thông báo không có quyền
     *
     * @var string $title Tiêu đề
     * @var string $message Thông báo
     * @var string $layout Tên layout hiển thị lỗi
     */
    public function viewPermissionError($title = '', $message = '', $layout = 'Permission')
    {
        $this->set('title', 'Yêu cầu bị từ chối');
        $this->set('message', 'Bạn không có quyền thực hiện việc này');
        
        if ($this->output != 'html') {
            $this->set('text', VHMIS_ERROR_NOTPERMISSION);
            $this->set('array', array('error' => 1, 'code' => VHMIS_ERROR_NOTPERMISSION, 'message' => 'You Do Not Have Permission'));
            $this->view();
            return;
        }
        
        $this->viewError($layout);
    }

    /**
     * Thực hiện download file
     *
     * @var string $path Đường dẫn của file
     * @var string $filename Tên file
     * @var string $filetype Loại file
     */
    public function download($path, $filename = '', $filetype = null)
    {
        // Nếu filename rỗng, thử lầy filename và path trong path
        if ($filename == '') {
            $filename = basename($path);
            $path = dirname($path);
        }
        
        $this->response->download($path, $filename, $filetype);
    }

    /**
     * Kiểm tra quyền
     *
     * @var string $action Hành động
     * @var string $resource Tài nguyên
     * @return bool Quyền
     */
    public function isAllow($action, $resource)
    {
        // Nếu kô có 2 thành phần auth với acl thì kô có quyền
        if ($this->components->auth === null || $this->components->acl === null)
            return false;
        
        return $this->components->acl->isAllow($this->user, $this->appUrl, $action, $resource);
    }

    /**
     * Thông báo lỗi nếu không có quyền
     *
     * @var string $action Hành động
     * @var string $resource Tài nguyên
     */
    public function checkAllow($action, $resource)
    {
        if (! $this->isAllow($action, $resource))
            $this->viewPermissionError();
    }

    /**
     * Kiểm tra tồn tại của các biến post (kiểm tra form submit với method post
     * có đúng và đủ các trường không)
     *
     * @var array $index Tên các biến post cần kiểm tra
     * @return boolean True nếu tất cả tồn tài, false nếu có 1 biến không tồn
     *         tại
     */
    protected function _checkPostData($index)
    {
        foreach ($index as $name) {
            if (! isset($this->request->post[$name])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Gọi các models được khai báo trước
     */
    protected function _loadModels()
    {
        if (is_array($this->_models)) {
            foreach ($this->_models as $model) {
                $this->_loadModel($model);
            }
        }
    }

    /**
     * Gọi Model
     *
     * @param
     *            string Tên model
     * @return Đối tượng của model đó
     */
    protected function _loadModel($model)
    {
        $var = ___ctv($model);
        
        // nếu đã tồn tại
        if ($this->models->$var != null)
            return $this->models->$var;
            
            // get db adapter for model;
        $name = explode('_', $model, 2);
        $name = $name[0];
        $db = $this->_db($name);
        
        // create model object
        return $this->models->load($model, array('db' => $db));
    }

    /**
     * Gọi các shares được khai báo trước
     */
    protected function _loadShares()
    {
        if (is_array($this->_shares)) {
            foreach ($this->_shares as $share) {
                $this->_loadShare($share);
            }
        }
    }

    /**
     * Gọi share
     *
     * @param string $data
     *            Tên Share
     * @return Đối tượng Share
     */
    protected function _loadShare($data)
    {
        $var = ___ctv($data);
        
        // nếu đã tồn tại
        if ($this->shares->$var != null)
            return $this->shares->$var;
            
            // load database nếu cần
        $name = explode('_', $data, 2);
        $this->_db($name[0]);
        
        // load shares
        return $this->shares->load($data);
    }

    /**
     * Kết nối database của app
     *
     * @param string $name
     *            Tên của app cần kết nối database
     */
    public function _db($name)
    {
        $name = strtolower($name);
        
        if (! Configure::isRegistered('Db' . ___fUpper($name))) {
            $config = ___loadConfig('Database', false);
            if (isset($config['databases'][$name])) {
                // Sử dụng chung db với app khác
                if (isset($config['databases'][$name]['use'])) {
                    $name2 = $config['databases'][$name]['use'];
                    if (! Configure::isRegistered('Db' . ___fUpper($name2))) {
                        $db = ___connectDb($config['databases'][$name2]);
                        if ($db != false) {
                            Configure::set('Db' . ___fUpper($name2), $db);
                            Configure::set('Db' . ___fUpper($name), $db);
                            return $db;
                        } else {
                            $this->viewDbError();
                            return;
                        }
                    } else {
                        return Configure::get('Db' . ___fUpper($name2));
                    }
                }
                
                // Sử dụng riêng
                $db = ___connectDb($config['databases'][$name]);
                if ($db != false) {
                    Configure::set('Db' . ___fUpper($name), $db);
                    return $db;
                } else {
                    $this->viewDbError();
                    return;
                }
            } else {
                $this->viewDbError();
                return;
            }
        }
        
        return Configure::get('Db' . ___fUpper($name));
    }

    /**
     * Hàm callback trước khi thực thi request
     */
    protected function _beforeInit()
    {
        return true;
    }

    /**
     * Hàm callback sau khi thực thi request
     */
    protected function _afterInit()
    {
        return true;
    }

    /**
     * Hàm callback trước khi gọi View
     */
    protected function _beforeLoadView()
    {
        return true;
    }

    /**
     * Hàm callback sau khi gọi View
     */
    protected function _afterLoadView()
    {
        return true;
    }

    /**
     * Lấy config của một app
     *
     * @param string $app
     *            Tên app
     * @return array null của app nếu có
     */
    protected function _loadAppConfig($app)
    {
        $config = ___loadAppConfig($app, false);
        if ($config != null) {
            return $config['apps']['info'][strtolower($app)];
        }
        
        return null;
    }
}