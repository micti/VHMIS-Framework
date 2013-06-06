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
    protected $template;

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
     * Thiết lập layout
     *
     * @param string $layout
     * @return \Vhmis\View\View
     */
    public function setLayout($name)
    {
        $this->layout = $name;
        return $this;
    }

    public function setApp($name)
    {
        $this->app = $name;
        return $this;
    }

    public function setController($name)
    {
        $this->controller = $name;
        return $this;
    }

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

    public function render()
    {
        return $this->renderHTML();
    }

    protected function renderHTML()
    {
        // Chuyển $data sang dạng biến với tên ứng với key
        extract($this->data);

        // Lấy view
        ob_start();

        include $this->getViewDirectory();

        $content = ob_get_clean();

        // Render view vào layout
        ob_start();

        include $this->getLayoutDirectory();

        $content = ob_get_clean();

        // Xuất kết quả cuối cùng
        return $content;
    }

    protected function getViewDirectory()
    {
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Apps' . D_SPEC . $this->app . D_SPEC . 'View' . D_SPEC
            . $this->controller . D_SPEC . $this->method . '.php';

        return $dir;
    }

    protected function getLayoutDirectory()
    {
        $dir = VHMIS_SYS2_PATH . D_SPEC . SYSTEM . D_SPEC . 'Template' . D_SPEC . $this->template . D_SPEC . 'Layout' . D_SPEC
            . $this->layout . '.php';

        return $dir;
    }
}
