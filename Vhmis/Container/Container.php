<?php

namespace Vhmis\Container;

use Vhmis\Container\Service\Classname;
use Vhmis\Container\Service\Object;
use Vhmis\Container\Service\ServiceInterface;

class Container
{
    /**
     * @var ServiceInterface[]
     */
    protected $services = [];

    /**
     * @var object[]
     */
    protected $instances = [];

    /**
     * @var bool[]
     */
    protected $isSharedServices = [];

    /**
     * Set service with class name.
     *
     * @param string $name
     *
     * @return bool|ServiceInterface
     */
    public function set($name)
    {
        return $this->setAlias($name, $name);
    }

    /**
     * Set service with name
     *
     * @param string        $name
     * @param string|object $service
     * @param bool          $share
     *
     * @return bool|ServiceInterface
     */
    public function setAlias($name, $service, $share = true)
    {
        if (is_object($service)) {
            $this->services[$name] = new Object($service);
        }

        if (is_string($service)) {
            $this->services[$name] = new Classname($service);
        }

        if (isset($this->services[$name])) {
            $this->services[$name]->setContainer($this);
            $this->isSharedServices[$name] = $share;

            // Remove old instance index
            unset($this->instances[$name]);

            return $this->services[$name];
        }

        return false;
    }

    public function get($name)
    {
        if (!$this->has($name)) {
            return null;
        }

        if (!$this->isSharedServices[$name]) {
            return $this->services[$name]->get();
        }

        if (!isset($this->instances[$name])) {
            $this->instances[$name] = $this->services[$name]->get();
        }

        return $this->instances[$name];
    }

    public function has($name)
    {
        if (isset($this->services[$name])) {
            return true;
        }

        if (class_exists($name, false)) {
            $this->set($name);

            return true;
        }

        return false;
    }
}
