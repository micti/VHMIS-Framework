<?php

namespace Vhmis\Container\Param;

use Vhmis\Container\ContainerAwareTrait;

class Service implements ParamInterface
{
    use ContainerAwareTrait;

    /**
     * Name of service
     *
     * @var string
     */
    protected $value;


    /**
     * Service param constructor.
     *
     * @param $value string Name of service
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get real value of param
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->container->get($this->value);
    }
}
