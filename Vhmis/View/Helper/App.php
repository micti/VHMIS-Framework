<?php

namespace Vhmis\View\Helper;

class App extends HelperAbstract
{

    public function __invoke($type = null)
    {
        $app = \Vhmis\Config\Config::system('Global', 'app/list');

        if ($type === null || $type === '' || $type === 'name') {
            return $app[$this->view->getAppUrl()]['name'];
        } else {
            return '';
        }
    }
}
