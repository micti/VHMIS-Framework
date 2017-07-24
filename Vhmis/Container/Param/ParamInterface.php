<?php

namespace Vhmis\Container\Param;

use Vhmis\Container\Container;

interface ParamInterface
{
    public function __construct($value);

    public function getValue();

    public function setContainer(Container $container);
}
