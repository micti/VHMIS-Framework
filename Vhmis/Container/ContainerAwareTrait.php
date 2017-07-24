<?php

namespace Vhmis\Container;

trait ContainerAwareTrait
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Set a container.
     *
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
