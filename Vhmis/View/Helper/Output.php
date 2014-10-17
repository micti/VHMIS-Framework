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
<<<<<<< HEAD
     * Call output as function
=======
     * Invoke
>>>>>>> 922d3c6849ba26fe52ed298d1881ef07964a2d92
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
<<<<<<< HEAD
     * Escape HTML body value.
     *
     * @param string $string
     *
     * @return string
=======
     * HTML content
     *
     * @param string $string
     *
     * @return type
>>>>>>> 922d3c6849ba26fe52ed298d1881ef07964a2d92
     */
    protected function processHtml($string)
    {
        return nl2br($this->escaper->escapeHtml($string), false);
    }

    /**
<<<<<<< HEAD
     * Escape CSS value.
     *
     * @param string $string
     *
     * @return string
=======
     * CSS content
     *
     * @param string $string
     *
     * @return type
>>>>>>> 922d3c6849ba26fe52ed298d1881ef07964a2d92
     */
    protected function processCss($string)
    {
        return $this->escaper->escapeCss($string);
    }

    /**
<<<<<<< HEAD
     * Escape JS value.
     *
     * @param string $string
     *
     * @return string
=======
     * JS content
     *
     * @param string $string
     *
     * @return type
>>>>>>> 922d3c6849ba26fe52ed298d1881ef07964a2d92
     */
    protected function processJs($string)
    {
        return $this->escaper->escapeJs($string);
    }

    /**
<<<<<<< HEAD
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

=======
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
>>>>>>> 922d3c6849ba26fe52ed298d1881ef07964a2d92
}
