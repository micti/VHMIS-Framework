<?php

namespace Vhmis\Cache\Adapter;

class Memcached implements StorageInterface
{

    /**
     * Đối tượng Memcached
     *
     * @var \Memcached
     */
    protected $memcached;

    /**
     * Khởi tạo
     *
     * @param array $options
     */
    public function __construct($options)
    {
        // Khởi tạo
        if (isset($options['persistent'])) {
            $this->memcached = $this->getMemecached($options['persistent']);
        } else {
            $this->memcached = $this->getMemecached();
        }

        // Thêm servers
        if (isset($options['servers'])) {
            foreach ($options['servers'] as $server) {
                $this->addServer($server['host'], $server['port'], $server['weight']);
            }
        }
    }

    /**
     * Thêm một server Memcached vào
     *
     * @param string $host
     * @param int    $port
     * @param int    $weight
     *
     * @return \Vhmis\Cache\Adapter\Memcached
     */
    public function addServer($host = 'localhost', $port = 11211, $weight = 0)
    {
        $this->memcached->addServer($host, $port, $weight);

        return $this;
    }

    /**
     */
    public function set($id, $value)
    {
        $this->memcached->add($id, $value);
    }

    /**
     *
     * @param  type $id
     * @return type
     */
    public function get($id)
    {
        return $this->memcached->get($id);
    }

    /**
     *
     * @param type $id
     */
    public function remove($id)
    {
        $this->memcached->delete($id);
    }

    /**
     *
     * @return boolean
     */
    public function removeAll()
    {
        return false;
    }

    /**
     * Khởi tạo một đối tượng Memcached
     *
     * @return \Memcached
     */
    protected function getMemecached()
    {
        return new \Memcached();
    }
}
