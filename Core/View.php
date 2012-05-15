<?php

class Vhmis_View
{
    /**
     * Data của controller gửi cho view
     *
     * @var array
     */
    protected $_data;

    /**
     * Thông tin controller
     *
     * @var array
     */
    protected $_dataController;

    /**
     * Cấu hình hệ thống
     *
     * @var array
     */
    protected $_dataConfig;

    /**
     * Nội dung của các block
     *
     * @var array
     */
    protected $_block = array();

    /**
     * Tên của các blog đang được tạo
     *
     * @var
     */
    protected $_activeBlock = array();

    /**
     * Tên của Template
     *
     * @var string
     */
    protected $_template = 'Default';

    /**
     * Tên của Layout
     *
     * @var string
     */
    protected $_layout = 'Default';

    /**
     * Đường dẫn đến File view của Action ở Controller
     *
     * @var string
     */
    protected $_view = '';

    /**
     * Tập hợp helpers gọi cho view
     *
     * @var Vhmis_Collection
     */
    public $helpers;
    public $templateHelpers;

    public function __construct()
    {
        $this->helpers = new Vhmis_Collection_Helpers();
        $this->templateHelpers = new Vhmis_Collection_Helpers();
    }

    /**
     * Gọi và tạo các đối tượng helper cho View
     *
     * @var string $helper Tên helpers cần gọi
     */
    public function loadHelpers($helpers)
    {
        foreach($helpers as $helper)
        {
            $this->loadHelper($helper);
        }
    }

    /**
     * Gọi và tạo đối tượng helper cho View
     *
     * @var string $helper Tên helpers cần gọi
     */
    public function loadHelper($helper)
    {
        return $this->helpers->load($helper);
    }

    /**
     * Gọi vào tạo đối tượng layout Helper cho View
     */
    public function loadTemplateHelpers($helpers)
    {
        foreach($helpers as $helper)
        {
            $this->loadTemplateHelper($helper);
        }
    }

    /**
     * Gọi vào tạo đối tượng layout Helper cho View
     */
    public function loadTemplateHelper($helper)
    {
        // Tên class
        $class = 'Vhmis_Template_' . $this->_template . '_Helper_' . $helper;

        // Tên biến lưu trong collection
        $name = ___ctv($helper);

        // Kiểm tra
        if($this->templateHelpers->$name != null) return $this->templateHelpers->$name;
        else // Tạo mới nếu chưa có
        {
            $this->_loadHelperFile($helper);
            $this->templateHelpers->$name = new $class();
            return $this->templateHelpers->$name;
        }
    }

    /**
     * Hàm render
     */
    public function render()
    {
        if($this->_dataController['app']['info']['output'] === null || $this->_dataController['app']['info']['output'] == '' || $this->_dataController['app']['info']['output'] == 'html')
        {
            $this->renderHTML();
            //return;
        }

        if($this->_dataController['app']['info']['output'] == 'shorttext')
        {
            $this->renderText();
            ///return;
        }

        if($this->_dataController['app']['info']['output'] == 'xml')
        {
            $this->renderXML();
            ///return;
        }

        if($this->_dataController['app']['info']['output'] == 'json')
        {
            $this->renderJSON();
            ///return;
        }
    }

    /**
     * Render Text
     */
    public function renderText()
    {
        $text = '';
        if(isset($this->_data['text'])) $text = $this->_data['text'];
        echo $text;
    }

    /**
     * Render XML
     */
    public function renderXML()
    {
        $array = array();
        if(isset($this->_data['array']) && is_array($this->_data['array'])) $array = $this->_data['array'];
        echo Vhmis_Xml::fromArray($array);
    }

    /**
     * Render XML
     */
    public function renderJSON()
    {
        $array = array();
        if(isset($this->_data['array']) && is_array($this->_data['array'])) $array = $this->_data['array'];
        echo json_encode($array);
    }

    /**
     * Lấy kết quả của view
     */
    protected function renderHTML()
    {
        // Chuyển $this->_data[$name] sang $name
        if(is_array($this->_data))
        {
            foreach($this->_data as $name => $data)
            {
                $$name = $data;
            }
        }

        // Chuyển $this->_dataController thành $appInfo và $userInfo
        $appInfo = $this->_dataController['app'];
        $userInfo = $this->_dataController['user'];

        // Chuyển $this->_dataConfig thành $config
        $config = $this->_dataConfig;
        $config['site']['fullclient'] = $config['site']['client'] . strtolower($this->_template) . '/';
        $config['site']['fullpath'] = $config['site']['path'] . $appInfo['url'] . '/';

        // Tồn tại config của View
        $configPath = VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . 'Config.php';
        if(file_exists($configPath)) include $configPath;

        // Tồn tại view
        if($this->_view !== false)
        {
            $view = VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC;
            if($this->_view != '') $view .=  $this->_view . '.php';
            else $view .= ___fUpper($this->_dataController['app']['info']['controller']) . D_SPEC . $this->_dataController['app']['info']['action'] . '.php';

            // Render view
            ob_start();
            include $view;
            $content = ob_get_clean();
        }

        // Render layout
        $layoutPath1 = VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Layout' . D_SPEC . $this->_layout . '.php';
        $layoutPath2 = VHMIS_SYS_PATH . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Layout' . D_SPEC . $this->_layout . '.php';
        $layoutPath3 = VHMIS_VIEW_PATH . D_SPEC . $this->_template . D_SPEC . '/Layout/' . $this->_layout . '.php';

        if(file_exists($layoutPath1)) include $layoutPath1;
        else if(file_exists($layoutPath2)) include $layoutPath2;
        else if(file_exists($layoutPath3)) include $layoutPath3;
        else echo 'Build layout';
    }

    public function renderError($layout = 'Default')
    {
        // Chuyển $this->_data[$name] sang $name
        if(is_array($this->_data))
        {
            foreach($this->_data as $name => $data)
            {
                $$name = $data;
            }
        }

        $config = $this->_dataConfig;
        $config['site']['fullclient'] = $config['site']['client'] . strtolower($this->_template) . '/';

        $layoutPath1 = VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Error' . D_SPEC . $layout . '.php';
        $layoutPath2 = VHMIS_SYS_PATH . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Error' . D_SPEC . $layout . '.php';
        $layoutPath3 = VHMIS_VIEW_PATH . D_SPEC . $this->_template . D_SPEC . 'Error' . D_SPEC . $layout . '.php';

        if(file_exists($layoutPath1)) include $layoutPath1;
        else if(file_exists($layoutPath2)) include $layoutPath2;
        else if(file_exists($layoutPath3)) include $layoutPath3;
        else echo 'Build error template';
    }

    /**
     * Thiết lập các thông tin liên quan đến view
     */
    public function setViewInfo($view = '', $layout = '', $template = '')
    {
        if($view != '') $this->_view = $view;
        if($layout != '') $this->_layout = $layout;
        if($template != '') $this->_template = $template;
    }

    /**
     * Chuyển các biến được set cho view ở Controller sang View
     */
    public function transferViewData($data)
    {
        $this->_data = $data;
    }

    /**
     * Chuyển thông tin controller sang cho View
     */
    public function transferControllerData($data)
    {
        $this->_dataController = $data;
    }

    /**
     * Chuyển thông tin cấu hình hệ thống sang cho View
     */
    public function transferConfigData($config)
    {
        $this->_dataConfig = $config;
    }

    /**
     * Render block
     */
    public function block($name, $data = null)
    {
        if(isset($this->_block[$name]))
        {
            echo $this->_block[$name];
        }

        $this->_loadBlockFile($name, $data);
    }

    /**
     * Make block
     */
    protected function _makeBlock($name)
    {
        $this->_activeBlock[] = $name;
        ob_start();
    }

    /**
     * Kết thúc tạo một block
     */
    protected function _endBlock()
    {
        if(!empty($this->_activeBlock))
        {
            $current = array_pop($this->_activeBlock);
            $this->_block[$current] = isset($this->_block[$current]) ? $this->_block[$current] . ob_get_clean() : ob_get_clean();
        }
    }

    /**
     * Load block from file
     */
    protected function _loadBlockFile($blockname, $data)
    {
        // Truyền biến
        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                $$key = $value;
            }
        }

        // Chuyển $this->_dataController thành $appInfo và $userInfo
        $appInfo = $this->_dataController['app'];
        $userInfo = $this->_dataController['user'];

        // Chuyển $this->_dataConfig thành $config
        $config = $this->_dataConfig;
        $config['site']['fullclient'] = $config['site']['client'] . strtolower($this->_template) . '/';
        $config['site']['fullpath'] = $config['site']['path'] . $appInfo['url'] . '/';

        // Gọi block
        if(file_exists(VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . $blockname . '.php'))
        {
            include VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . $blockname . '.php';
            return;
        }

        if(file_exists(VHMIS_VIEW_PATH . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . $blockname . '.php'))
        {
            include VHMIS_VIEW_PATH . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . $blockname . '.php';
            return;
        }
    }

    /**
     * Load file helper của người dùng
     */
    protected function _loadHelperFile($helper)
    {
        $helperPath1 = VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Helper' . D_SPEC . $helper . '.php';
        $helperPath2 = VHMIS_SYS_PATH . D_SPEC . 'View' . D_SPEC . $this->_template . D_SPEC . '_Helper' . D_SPEC . $helper . '.php';
        $helperPath3 = VHMIS_VIEW_PATH . D_SPEC . $this->_template . D_SPEC . 'Helper' . D_SPEC . $helper . '.php';

        if(file_exists($helperPath1)) include $helperPath1;
        else if(file_exists($helperPath2)) include $helperPath2;
        else if(file_exists($helperPath3)) include $helperPath3;
        else
        {
            echo 'Not found helper file';
            exit();
        }
    }
}
?>