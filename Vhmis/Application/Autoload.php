<?php

namespace Vhmis\Application;

/**
 * Class dùng để gọi tự động các file class
 */
class Autoload
{

    /**
     * Tên namespace
     *
     * @var string
     */
    protected $ns;

    /**
     * Đường dẫn chứa file
     *
     * @var string
     */
    protected $path;

    /**
     * Ký hiệu phân cách trong namespace
     *
     * @var string
     */
    protected $nsSep = '\\';

    /**
     * Khởi tạo
     *
     * @param string $namespace Tên chính của Namespace
     * @param string $path Đường dẫn tới thư mục chứa các file
     */
    function __construct($namespace = null, $path = null)
    {
        $this->ns = $namespace;
        $this->path = $path;
    }

    /**
     * Khai báo autoload
     */
    public function register()
    {
        spl_autoload_register(array(
            $this,
            'load'
        ));
    }

    /**
     * Khai báo thôi autoload
     */
    public function unregister()
    {
        spl_autoload_unregister(array(
            $this,
            'load'
        ));
    }

    /**
     * Load class, tên class (namespace) được đặt theo PCR_0
     *
     * @param string $class
     */
    function load($class)
    {
        if ($this->ns === null || $this->ns . $this->nsSep === substr($class, 0, strlen($this->ns . $this->nsSep))) {
            $file = '';
            $lastNsPos = strrpos($class, $this->nsSep);

            if ($lastNsPos !== false) {
                $namespace = substr($class, 0, $lastNsPos);
                $class = substr($class, $lastNsPos + 1);
                $file = str_replace($this->nsSep, D_SPEC, $namespace);
            }

            $file .= D_SPEC . str_replace('_', D_SPEC, $class);
            $file = $this->path . D_SPEC . $file . '.php';

            /* tạm thời */
            if ($this->ns === 'VhmisApps') {
                $file = str_replace('VhmisApps', 'Apps', $file);
            }

            include $file;
        }
    }

    public static function autoload($path)
    {

    }
}
