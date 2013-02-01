<?php
namespace Vhmis\View\Helper;
use Vhmis\Text\Escaper;

/**
 * Description of Escape
 *
 * @package Vhmis_View
 * @subpackage Helper
 */
class Escape
{

    /**
     * Đối tượng Escaper
     *
     * @var Escaper
     */
    protected $_escaper;

    public function __construct()
    {
        $this->_escaper = new Escaper();
    }

    public function html($text)
    {
        return $this->_escaper->escapeHtml($text);
    }
}