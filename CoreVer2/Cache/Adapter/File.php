<?php

namespace Vhmis\Cache\Adapter;

class File implements StorageInterface
{
    /**
     * Thư mục lưu cache
     *
     * @var string
     */
    protected $_path;

    /**
     *
     * @param type $options
     * @throws \LogicException
     */
    public function __construct($options)
    {
        if(!isset($options['path'])) {
            throw new \LogicException("Chưa set đường dẫn thư mục chứa file cache.");
        }

        if(!is_writable($options['path'])) {
            throw new \LogicException("Không có quyền ghi vào thư mục.");
        }

        $this->_path = $options['path'];
    }

    public function set($id, $value)
    {
        $file = $this->_path . D_SPEC . $id;
        $value = serialize($value);
        file_put_contents($file, $value);
    }

    public function get($id)
    {
        $file = $this->_path . D_SPEC . $id;

        if(is_readable($file)) {
            $value = file_get_contents($file);
            $value = unserialize($value);

            return $value;
        }

        return null;
    }

    public function remove($id)
    {
        $file = $this->_path . D_SPEC . $id;
        @unlink($file);
    }

    public function removeAll()
    {
        return false;
    }
}
