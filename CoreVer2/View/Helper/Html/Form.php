<?php
namespace Vhmis\View\Helper\Html;

/**
 * Hổ trợ tạo mã HTML cho form
 */
class Form extends HtmlAbstract
{

    protected $_normalAttributes = array('method' => 'get', 'id' => '', 'class' => '', 'target' => '', 'action' => '', 'name' => '');

    public function __invoke($attributes)
    {
        return $this->open($attributes);
    }

    public function open($attributes)
    {
        return '<form ' . $this->_attribute($attributes) . '>';
    }

    public function close()
    {
        return '</form>';
    }
}