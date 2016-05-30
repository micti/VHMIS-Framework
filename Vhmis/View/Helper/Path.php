<?php

namespace Vhmis\View\Helper;

class Path extends HelperAbstract
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

        $this->path['site'] = $site['path'];
        $this->path['client'] = $site['client'];
        $this->path['avatar'] = $site['avatar'];
        $this->path['stu34'] = $site['student34'];
        $this->path['scvphoto'] = $site['scvphoto'];
    }

    public function __invoke($path = null)
    {
        if ($path === null || $path === '') {
            return $this->path['site'];
        }
        if (isset($this->path[$path])) {
            return $this->path[$path];
        } else {
            if ($path === 'app') {
                $this->path['app'] = $this->path['site'] . $this->view->getAppUrl();
                return $this->path['app'];
            } else {
                return '';
            }
        }
    }
}
