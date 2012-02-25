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
        return;
    }

    /**
     * Render XML
     */
    public function renderJSON()
    {
        return;
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

    public function renderDbError()
    {
        $this->renderError();
    }

    public function renderError($layout = 'Default', $title = '', $message = '')
    {
        // Chuyển $this->_data[$name] sang $name
        if(is_array($this->_data))
        {
            foreach($this->_data as $name => $data)
            {
                $$name = $data;
            }
        }

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
    protected function _loadBlockFile($name, $data)
    {
        // Truyền biến
        if(is_array($data))
        {
            foreach($data as $name => $value)
            {
                $$name = $value;
            }
        }

        // Gọi block
        if(file_exists(VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . ___fUpper($name) . '.php'))
        {
            include VHMIS_APPS_PATH . D_SPEC . ___fUpper($this->_dataController['app']['url']) . D_SPEC . 'View' . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . ___fUpper($name) . '.php';
            return;
        }

        if(file_exists(VHMIS_VIEW_PATH . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . ___fUpper($name) . '.php'))
        {
            include VHMIS_VIEW_PATH . D_SPEC . 'Default' . D_SPEC . '_Blocks' . D_SPEC . ___fUpper($name) . '.php';
            return;
        }
    }
}
?>