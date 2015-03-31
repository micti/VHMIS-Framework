<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\View;

/**
 * View
 */
class View
{
    /**
     * Data from controller
     *
     * @var array
     */
    protected $data = array();

    /**
     * Template
     *
     * @var string
     */
    protected $template;

    /**
     * Layout
     *
     * @var string
     */
    protected $layout;

    /**
     * View/method
     *
     * @var string
     */
    protected $method;

    /**
     * Output
     *
     * @var string
     */
    protected $output = 'html';

    /**
     * App
     *
     * @var string
     */
    protected $app;

    /**
     * App url
     *
     * @var string
     */
    protected $appUrl;

    /**
     * Controller
     *
     * @var string
     */
    protected $controller;

    /**
     * User info
     *
     * @var array
     */
    protected $user;

    /**
     * No view
     *
     * @var boolean
     */
    protected $noView = false;

    /**
     * Helpers class
     *
     * @var array
     */
    protected $helperClass = array(
        'path'     => 'Path',
        'appInfo'  => 'App',
        'dateTime' => 'DateTime',
        'number'   => 'Number',
        'output'   => 'Output',
        'dtRelative' => 'DateTimeRelative'
    );

    /**
     * Helper object
     *
     * @var array
     */
    protected $helpers = array();

    /**
     * Set template
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setTemplate($name)
    {
        $this->template = $name;

        return $this;
    }

    /**
     * Set laypout
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setLayout($name)
    {
        $this->layout = $name;

        return $this;
    }

    /**
     * Set app
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setApp($name)
    {
        $this->app = $name;

        return $this;
    }

    /**
     * Set app url
     *
     * @param string $url
     *
     * @return \Vhmis\View\View
     */
    public function setAppUrl($url)
    {
        $this->appUrl = $url;

        return $this;
    }

    /**
     * Get app url
     *
     * @return string
     */
    public function getAppUrl()
    {
        return $this->appUrl;
    }

    /**
     * Set controller
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setController($name)
    {
        $this->controller = $name;

        return $this;
    }

    /**
     * Set method/view
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setView($name)
    {
        $this->method = $name;

        return $this;
    }

    /**
     * Set output
     *
     * @param string $name
     *
     * @return \Vhmis\View\View
     */
    public function setOutput($name)
    {
        $this->output = $name;

        return $this;
    }

    /**
     * Set user info
     *
     * @param array $user
     *
     * @return \Vhmis\View\View
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set data for view
     *
     * @param string $key
     * @param mixed $data
     *
     * @return \Vhmis\View\View
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }

    /**
     * Set noview, skip view, call layout
     *
     * @return \Vhmis\View\View
     */
    public function setNoView()
    {
        $this->noView = true;

        return $this;
    }

    /**
     * Render view
     *
     * @return string
     */
    public function render($data = null)
    {
        if ($this->output === 'text') {
            return $this->renderText($data);
        }

        if ($this->output === 'json') {
            return $this->renderJson($data);
        }

        return $this->renderHtml($data);
    }

    /**
     * Render view as full html page
     *
     * @return string
     */
    protected function renderHtml($data)
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

        if (is_array($data)) {
            extract($data);
        }

        // Lấy view
        if (!$this->noView) {
            ob_start();

            include $this->getViewBoot();

            include $this->getViewDirectory();

            $content = ob_get_clean();
        } else {
            $content = '';
        }

        // Render view vào layout nếu có
        if ($this->layout !== null && $this->layout !== '') {
            ob_start();

            include $this->getLayoutDirectory();

            $content = ob_get_clean();
        }

        // Trả kết quả cuối cùng cho response
        return $content;
    }

    /**
     * Render view as text
     *
     * @param mixed $data
     * @return string
     */
    protected function renderText($data)
    {
        if (!$this->noView) {
            // Chuyển $data sang dạng biến với tên ứng với key
            extract($this->data);

            if (is_array($data)) {
                extract($data);
            }

            // Lấy view
            ob_start();

            include $this->getViewBoot();

            include $this->getViewDirectory();

            $content = ob_get_clean();

            // Trả kết quả cuối cùng cho response
            return $content;
        }

        if (is_string($data)) {
            return $data;
        }

        if (is_array($data)) {
            return $data['message'];
        }

        return 'nothing returns';
    }

    /**
     * Render view as json
     *
     * @param array $data
     * @return string
     */
    protected function renderJson($data = null)
    {
        if ($data === null || !is_array($data)) {
            return json_encode(array());
        }

        return json_encode($data);
    }

    /**
     * Magic __call method to call helper as function or get helper object
     *
     * @param string $name
     *
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if (!isset($this->helpers[$name])) {
            $this->helpers[$name] = $this->getHelper($name);
        }

        if (is_callable($this->helpers[$name])) {
            return call_user_func_array($this->helpers[$name], $arguments);
        }

        return $this->helpers[$name];
    }

    /**
     * Get helper
     *
     * @param string $name
     *
     * @return object
     *
     * @throws \Exception
     */
    protected function getHelper($name)
    {
        if (!isset($this->helperClass[$name])) {
            throw new \Exception('Helper ' . $name . ' is not found.');
        }

        $class = '\Vhmis\View\Helper\\' . $this->helperClass[$name];

        $helper = new $class;
        $helper->setView($this);

        return $helper;
    }

    /**
     * Render small block of app
     *
     * @param string $app
     * @param string $name
     * @param array $data
     *
     * @return string
     */
    protected function appBlock($app, $name, $data = array())
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

        extract($data);

        // Lấy block
        ob_start();

        include $this->getAppBlockDirectory($app, $name);

        $content = ob_get_clean();

        // Xuất kết quả
        echo $content;
    }

    /**
     * Render small block of system
     *
     * @param string $name
     * @param array $data
     *
     * @return string
     */
    protected function block($name, $data = array())
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

        extract($data);

        // Lấy block
        ob_start();

        include $this->getBlockDirectory($name);

        $content = ob_get_clean();

        // Xuất kết quả
        echo $content;
    }

    /**
     * Lấy đường dẫn file view
     *
     * @return string
     */
    protected function getViewDirectory()
    {
        $dir = VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $this->app . D_SPEC . 'View' . D_SPEC
            . $this->template . D_SPEC . $this->controller . D_SPEC . $this->method;

        if ($this->output === 'text') {
            $dir .= '_Text';
        }

        return $dir . '.php';
    }

    /**
     * Lấy đường dẫn file view
     *
     * @return string
     */
    protected function getViewBoot()
    {
        $dir = VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $this->app . D_SPEC . 'View' . D_SPEC
            . $this->template . D_SPEC . 'boot.php';

        return $dir;
    }

    /**
     * Lấy đường dẫn file block của 1 app
     *
     * @param string $app Tên app
     * @param string $name Tên block
     * @return string
     */
    protected function getAppBlockDirectory($app, $name)
    {
        $dir = VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $app . D_SPEC . 'View' . D_SPEC
            . $this->template . D_SPEC . 'Block' . D_SPEC . $name . '.php';

        return $dir;
    }

    /**
     * Lấy đường dẫn block chung của template
     *
     * @param string $name Tên block
     * @return string
     */
    protected function getBlockDirectory($name)
    {
        $dir = VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Template' . D_SPEC . $this->template . D_SPEC . 'Block' . D_SPEC
            . $name . '.php';

        return $dir;
    }

    /**
     * Lấy đường dẫn file layout
     *
     * @return string
     */
    protected function getLayoutDirectory()
    {
        $dir = VHMIS_SYS_PATH . D_SPEC . SYSTEM . D_SPEC . 'Template' . D_SPEC . $this->template . D_SPEC . 'Layout' . D_SPEC
            . $this->layout . '.php';

        return $dir;
    }
}
