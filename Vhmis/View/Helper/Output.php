<?php

namespace Vhmis\View\Helper;

use Vhmis\Text\Escaper;

class Output extends HelperAbstract
{
    /**
     * Escaper object
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->escaper = new Escaper;
    }

    /**
     * Invoke
     *
     * @param string $string
     * @param string $process
     *
     * @return string
     */
    public function __invoke($string, $process = 'html')
    {
        $processes = explode('|', $process);
        foreach ($processes as $process) {
            $method = 'process' . $process;
            $string = $this->$method($string);
        }

        return $string;
    }

    /**
     * HTML content
     *
     * @param string $string
     *
     * @return type
     */
    protected function processHtml($string)
    {
        return nl2br($this->escaper->escapeHtml($string), false);
    }

    /**
     * CSS content
     *
     * @param string $string
     *
     * @return type
     */
    protected function processCss($string)
    {
        return $this->escaper->escapeCss($string);
    }

    /**
     * JS content
     *
     * @param string $string
     *
     * @return type
     */
    protected function processJs($string)
    {
        return $this->escaper->escapeJs($string);
    }

    /**
     * HTML Attribute content
     *
     * @param string $string
     *
     * @return type
     */
    protected function processHtmlattr($string)
    {
        return $this->escaper->escapeHtmlAttr($string);
    }
}
