<?php

namespace Vhmis\Cache\Adapter;

class Memcached implements StorageInterface
{
    /**
     * Đối tượng Memcached
     *
     * @var \Memcached
     */
    protected $_memcached;

    public function __construct($options)
    {
        // Khởi tạo
        if(isset($options['persistent'])) {
            $this->_memcached = $this->_getMemecached($options['persistent']);
        } else {
            $this->_memcached = $this->_getMemecached();
        }

        // Thêm servers
        if(isset($options['servers'])) {
            foreach($options['servers'] as $server) {
                $this->addServer($server['host'], $server['port'], $server['weight']);
            }
        }
    }

    public function addServer($host = 'localhost', $port =  11211, $weight = 0)
    {
        $this->_memcached->addServer($host, $port, $weight);

        return $this;
    }

    public function set($id, $value, $options = array())
    {
        $this->_memcached->add($id, $value);
    }

    public function get($id)
    {
        return $this->_memcached->get($id);
    }

    public function remove($id)
    {
        $this->_memcached->delete($id);
    }

    public function removeAll()
    {
        return false;
    }

    /**
     * Khởi tạo một đối tượng Memcached
     *
     * @return \Memcached
     */
    protected function _getMemecached()
    {
        return new \Memcached;
    }
}
