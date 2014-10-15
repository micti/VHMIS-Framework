<?php

namespace Vhmis\View\Helper;

use Vhmis\Text\Escaper;

class Output extends HelperAbstract
{

    /**
     * Escaper object
     *
     * @var \Vhmis\Text\Escaper
     */
    protected $escaper;

    public function __construct()
    {
        $this->escaper = new Escaper;
    }

    /**
     * Call output as function
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
     * Escape HTML body value.
     *
     * @param string $string
     *
     * @return string
     */
    protected function processHtml($string)
    {
        return nl2br($this->escaper->escapeHtml($string), false);
    }

    /**
     * Escape CSS value.
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
     * Escape JS value.
     *
     * @param string $string
     *
     * @return string
     */
    protected function processJs($string)
    {
        return $this->escaper->escapeJs($string);
    }

    /**
     * Escape HTML Attribute value.
     *
     * @param string $string
     *
     * @return string
     */
    protected function processHtmlAttr($string)
    {
        return $this->escaper->escapeHtmlAttr($string);
    }

    /**
     * Escape URL or Paramater value.
     *
     * @param string $string
     *
     * @return string
     */
    protected function processUrl($string)
    {
        return $this->escaper->escapeUrl($string);
    }

}
