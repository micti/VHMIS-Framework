<?php

namespace Vhmis\View;

class View
{
    /**
     * Mảng chứa dữ liệu từ Controller truyền sang View
     *
     * @var array
     */
    protected $data = array();

    /**
     * Tên template
     *
     * @var string
     */
    protected $template;

    /**
     * Tên layout
     *
     * @var string
     */
    protected $layout;

    /**
     * Tên view/method
     *
     * @var string
     */
    protected $method;

    /**
     * Tên app
     *
     * @var string
     */
    protected $app;

    /**
     * Tên url của app
     *
     * @var string
     */
    protected $appUrl;

    /**
     * Tên controller
     *
     * @var string
     */
    protected $controller;

    /**
     * Thông tin người dùng / người đăng nhập
     *
     * @var array
     */
    protected $user;

    /**
     * Không sử dụng view của controller/method
     */
    protected $noView = false;

    /**
     * Danh sách các helper
     *
     * @var array
     */
    protected $helperList = array(
        'path'     => 'Path',
        'appInfo'  => 'App',
        'dateTime' => 'DateTime',
        'number'   => 'Number'
    );

    /**
     * Các đối tượng Helper đã được tạo
     *
     * @var array
     */
    protected $helpers = array();

    /**
     * Thiết lập template
     *
     * @param string $name
     * @return \Vhmis\View\View
     */
    public function setTemplate($name)
    {
        $this->template = $name;
        return $this;
    }

    /**
     * Thiết lập layout, nếu không sử dụng layout, vui lòng để trống hoặc không thiết lập
     *
     * @param string $layout
     * @return \Vhmis\View\View
     */
    public function setLayout($name)
    {
        $this->layout = $name;
        return $this;
    }

    /**
     * Thiết lập tên App
     *
     * @param string $name
     * @return \Vhmis\View\View
     */
    public function setApp($name)
    {
        $this->app = $name;
        return $this;
    }

    /**
     * Thiết lập url của App
     *
     * @param string $url
     * @return \Vhmis\View\View
     */
    public function setAppUrl($url)
    {
        $this->appUrl = $url;
        return $this;
    }

    /**
     * Lấy url của app
     *
     * @return string
     */
    public function getAppUrl()
    {
        return $this->appUrl;
    }

    /**
     * Thiết lập tên Controller
     *
     * @param type $name
     * @return \Vhmis\View\View
     */
    public function setController($name)
    {
        $this->controller = $name;
        return $this;
    }

    /**
     * Thiết lập method, hay chính là view
     *
     * @param type $name
     * @return \Vhmis\View\View
     */
    public function setMethod($name)
    {
        $this->method = $name;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Thiết lập 1 dữ liệu mới cho view
     *
     * @param string $key
     * @param mixed $data
     * @return \Vhmis\View\View
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }

    /**
     * Thiết lập không sử dụng view, gọi thẳng layout
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
    public function render()
    {
        return $this->renderText();
    }

    /**
     * Render dạng text
     *
     * @return string
     */
    protected function renderText()
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

        // Lấy view
        if (!$this->noView) {
            ob_start();

            include $this->getViewBoot();

            include $this->getViewDirectory();

            $content = ob_get_clean();
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
     * Sử dụng để gọi các helper
     *
     * Mỗi helper có 1 tên, tên của helper và tên class của helper được cấu hình trong property $helperName;
     *
     * Nếu class helper có khai báo phương __invoke, thì helper sẽ được gọi như function
     * Nếu không, đối tượng được tạo ra từ helper sẽ được trả về
     *
     * @param string $name
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

    protected function getHelper($name)
    {
        if (!isset($this->helperList[$name])) {
            throw new \Exception('Helper ' . $name . ' is not found.');
        }

        $class = '\Vhmis\View\Helper\\' . $this->helperList[$name];

        $helper = new $class;
        $helper->setView($this);

        return $helper;
    }

    /**
     * Lấy các block nhỏ của app đặt vào view
     *
     * Ở file view, gọi block bằng cách $this->getAppBlock(...);
     *
     * @param string $app
     * @param string $name
     * @param array $data
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
     * Lấy các block nhỏ của hệ thống đặt vào view
     *
     * Ở file view, gọi block bằng cách $this->getBlock(...);
     *
     * @param string $app
     * @param string $name
     * @param array $data
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
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $this->app . D_SPEC . 'View' . D_SPEC
            . $this->template . D_SPEC . $this->controller . D_SPEC . $this->method . '.php';

        return $dir;
    }

    /**
     * Lấy đường dẫn file view
     *
     * @return string
     */
    protected function getViewBoot()
    {
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $this->app . D_SPEC . 'View' . D_SPEC
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
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $app . D_SPEC . 'View' . D_SPEC
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
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Template' . D_SPEC . $this->template . D_SPEC . 'Block' . D_SPEC
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
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Template' . D_SPEC . $this->template . D_SPEC . 'Layout' . D_SPEC
            . $this->layout . '.php';

        return $dir;
    }
}
