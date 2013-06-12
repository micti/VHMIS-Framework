<?php

namespace Vhmis\View\Helper;

class App extends HelperAbstract
{

    public function __invoke($type = null)
    {
        $app = \Vhmis\Config\Config::system('Applications', 'list');

        if ($type === null || $type === '' || $type === 'name') {
            return $app['name'][$this->view->getAppUrl()];
        } else {
            return '';
        }
    }
}
