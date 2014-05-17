<?php

namespace Vhmis\Component;

use \Vhmis\Di\ServiceManagerAwareInterface;
use \Vhmis\Di\ServiceManager;

/**
 * Component
 */
class Component implements ServiceManagerAwareInterface
{
    /**
     *
     * @var \Vhmis\Di\ServiceManager
     */
    protected $sm;

    /**
     * Thiết lập Service Manager
     *
     * @param \Vhmis\Di\ServiceManager $sm
     */
    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
    }
}
