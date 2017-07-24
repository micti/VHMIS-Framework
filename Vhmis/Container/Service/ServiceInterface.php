<?php

namespace Vhmis\Container\Service;

use Vhmis\Container\Container;

interface ServiceInterface
{
    public function __construct($object);

    public function get();

    /**
     * @param $container
     *
     * @return self
     */
    public function setContainer(Container $container);
}
