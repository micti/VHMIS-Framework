<?php

namespace Vhmis\View\Helper;

class Path
{
    /**
     *
     * @var array
     */
    protected $path;

    public function __construct()
    {
        $path = \Vhmis\Config\Config::system('Global', 'path');

        $site = \Vhmis\Config\Config::system('Global', 'site');

        $this->path['path'] = $site['path'];
        $this->path['client'] = $site['client'];
    }

    public function __invoke($path = null)
    {
        if($path === null || $path === '') {
            return $this->path['path'];
        }
        if (isset($this->path[$path])) {
            return $this->path[$path];
        } else {
            return '';
        }
    }
}
