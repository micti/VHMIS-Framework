<?php

namespace Vhmis\View\Helper;

use \Vhmis\View\View;

abstract class HelperAbstract
{
    /**
     *
     * @var \Vhmis\View\View
     */
    protected $view;

    public function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }
}
