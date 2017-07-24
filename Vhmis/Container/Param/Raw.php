<?php

namespace Vhmis\Container\Param;


use Vhmis\Container\ContainerAwareTrait;

class Raw implements ParamInterface
{
    use ContainerAwareTrait;

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
