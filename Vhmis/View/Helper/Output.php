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
     * @param string $process one or more processes Html|Format ...
     *
     * @return string
     */
    public function __invoke($string, $process = 'Html')
    {
        $processes = explode('|', $process);
        foreach ($processes as $process) {
            $method = 'process' . $process;
            $string = $this->$method($string);
        }

        return $string;
    }

    /**
     * Format content
     * + nl2Br -> Newline \n to <br> tag
     *
     * @param string $string
     *
     * @return string
     */
    protected function processFormat($string)
    {
        return nl2br($string);
    }

    /**
     * HTML content
     *
     * @param string $string
     *
     * @return string
     */
    protected function processHtml($string)
    {
        return $this->escaper->escapeHtml($string);
    }

    /**
     * CSS content
     *
     * @param string $string
     *
     * @return string
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
     * @return string
     */
    protected function processHtmlattr($string)
    {
        return $this->escaper->escapeHtmlAttr($string);
    }
}
