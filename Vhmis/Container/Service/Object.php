<?php

namespace Vhmis\Container\Service;

use Closure;
use Vhmis\Container\ContainerAwareTrait;
use Vhmis\Container\Param\ParamInterface;

class Object implements ServiceInterface
{
    use ContainerAwareTrait;
    use ParamsValueTrait;

    protected $params;
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @param ParamInterface[] $params
     *
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get object
     *
     * @return mixed
     */
    public function get()
    {
        if ($this->object instanceof Closure) {
            if ($this->params) {
                return call_user_func_array($this->object, $this->getRealValueOfParams($this->params));
            }

            return call_user_func($this->object);
        }

        return $this->object;
    }
}
