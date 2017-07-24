<?php

namespace Vhmis\Container\Service;

use ReflectionClass;
use Vhmis\Container\ContainerAwareTrait;
use Vhmis\Container\Param\ParamInterface;

class Classname implements ServiceInterface
{
    use ContainerAwareTrait;
    use ParamsValueTrait;

    protected $constructParams = [];
    protected $methods = [];
    protected $object;

    public function __construct($class)
    {
        $this->object = $class;
    }

    /**
     * @param ParamInterface[] $params
     *
     * @return self
     */
    public function setConstructorParams($params)
    {
        $this->constructParams = $params;

        return $this;
    }

    public function setMethod($name, $params) {
        $this->methods[$name] = $params;
        return $this;
    }

    /**
     * Get object.
     *
     * @return object
     */
    public function get()
    {
        $instance = $this->createInstance();

        foreach($this->methods as $method => $params) {
            if (!$params) {
                $instance->$method();
                continue;
            }

            $instance->$method(...$this->getRealValueOfParams($params));
        }

        return $instance;
    }

    /**
     * Create new object.
     *
     * @return object
     */
    protected function createInstance() {
        $classRef = new ReflectionClass($this->object);

        if (!$classRef->getConstructor()) {
            return $classRef->newInstanceWithoutConstructor();
        }

        return $classRef->newInstanceArgs($this->getRealValueOfParams($this->constructParams));
    }
}
