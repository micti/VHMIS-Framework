<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Vhmis\Controller;

use \Vhmis\Network;
use \Vhmis\Config\Configure;
use \Vhmis\Di\ServiceManager;

/**
 * Controller
 *
 * @category Vhmis
 * @package Vhmis_Controller
 */
class Controller implements \Vhmis\Di\ServiceManagerAwareInterface
{

    /**
     * Thông tin Apps và Request (chủ yếu dùng khi chuyển qua đối tượng khác).
     */
    public $appInfo;

    /**
     * Tên App
     */
    public $app;

    /**
     * Tên url cua app (dung de lam dia chi, dat ten bien).
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
    public $params;

    /**
     * Kiểu xuất ra
     */
    public $output;

    /**
     * Container
     *
     * @var \Vhmis\Di\ServiceManager
     */
    public $sm;

    /**
     * Khởi tạo
     *
     * @param \Vhmis\Network\Request $request
     * @param \Vhmis\Network\Response $response
     */
    public function __construct(Network\Request $request = null, Network\Response $response = null)
    {
        $this->request = $request != null ?  : new Network\Request();
        $this->response = $response != null ?  : new Network\Response();

        $this->appInfo = $request->app;
        $this->app = $this->appInfo['app'];
        // $this->appUrl = $this->appInfo['url'];

        $this->action = $this->_action = $this->appInfo['action'];
        $this->params = $this->_params = $this->appInfo['params'];
        $this->output = $this->_output = $this->appInfo['output'];
        $this->controller = $this->appInfo['controller'];
    }

    /**
     * Thiết lập Service Manager
     *
     * @param \Vhmis\Di\ServiceManager $sm
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
    }

    /**
     * Thực thi request
     */
    public function init()
    {
        $action = 'action' . $this->_action;

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            echo 'Not found ' . $this->_action . ' action. Create new method : ' . $action;
            exit();
        }
    }

    /**
     * Lấy model, sử dụng tên class (bắt đầu từ tên App)
     *
     * Ví dụ \YourSystem\Apps\App1\Model\Model1 thì tên model là App1\Model\Model1
     *
     * @param string $model Tên Model
     * @return \Vhmis\Db\ModelInterface
     */
    protected function getModel($model)
    {
        return $this->sm->getModel($model);
    }
}