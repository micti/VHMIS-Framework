<?php

namespace Vhmis\Container\Param;


use Vhmis\Container\ContainerAwareTrait;

class Raw implements ParamInterface
{
    use ContainerAwareTrait;

    /**
     * Raw value
     *
     * @var string
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
