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
    public function addConstructorParams($params)
    {
        $this->constructParams = [];

        foreach ($params as $param) {
            $param->setContainer($this->container);
            $this->constructParams[] = $param;
        }

        return $this;
    }

    public function addMethod($name, $params) {
        $this->methods[$name] = $params;
        return $this;
    }

    public function get()
    {
        $instance = $this->createInstance();

        foreach($this->methods as $method => $params) {
            if (!$params) {
                $instance->$method();
                continue;
            }

            call_user_func_array(array($instance, $method), $this->getRealValueOfParams($params));
        }

        return $instance;
    }

    protected function createInstance() {
        $classRef = new ReflectionClass($this->object);

        if (!$classRef->getConstructor()) {
            return $classRef->newInstanceWithoutConstructor();
        }

        return $classRef->newInstanceArgs($this->getRealValueOfParams($this->constructParams));
    }

    protected function reflect()
    {
        return new ReflectionClass($this->object);
    }
}
