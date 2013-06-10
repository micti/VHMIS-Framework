<?php

namespace Vhmis\View;

class View
{
    /**
     * Mảng chứa dữ liệu từ Controller truyền sang View
     *
     * @var array
     */
    protected $data;

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
     * Tên controller
     *
     * @var string
     */
    protected $controller;

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
        ob_start();

        include $this->getViewDirectory();

        $content = ob_get_clean();

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
     * Lấy các block nhỏ của app đặt vào view
     *
     * Ở file view, gọi block bằng cách $this->getAppBlock(...);
     *
     * @param string $app
     * @param string $name
     * @param array $data
     * @return string
     */
    protected function getAppBlock($app, $name, $data = array())
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

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
    protected function getBlock($name, $data = array())
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

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
