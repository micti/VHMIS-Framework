<?php

namespace Vhmis\View\Helper;

use Vhmis\Text\Escaper;

class Output extends HelperAbstract
{
    /**
     *
     * @var \Vhmis\Text\Escaper
     */
    protected $escaper;

    public function __construct()
    {
        $this->escaper = new Escaper;
    }

    public function __invoke($string, $process = 'html')
    {
        $processes = explode('|', $process);
        foreach ($processes as $process) {
            $method = 'process' . ucfirst(strtolower($process));
            $string = $this->$method($string);
        }

        return $string;
    }

    protected function processHtml($string)
    {
        return nl2br($this->escaper->escapeHtml($string), false);
    }

    protected function processCss($string)
    {
        return $this->escaper->escapeCss($string);
    }

    protected function processJs($string)
    {
        return $this->escaper->escapeJs($string);
    }

    protected function processHtmlattr($string)
    {
        return $this->escaper->escapeHtmlAttr($string);
    }
}