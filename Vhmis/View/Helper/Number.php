<?php

namespace Vhmis\View\Helper;

class Number extends HelperAbstract
{
    /**
     * Đối tượng number output
     *
     * @var \Vhmis\I18n\Output\Number
     */
    protected $dt;

    public function __construct()
    {
        $this->dt = new \Vhmis\I18n\Output\Number();
    }

    /**
     *
     * @param string|int|float $number
     * @param string $type
     * @param string $locale
     * @return string
     */
    public function __invoke($number, $type, $locale = '')
    {
        if($type === 'int')
        {
            return $this->dt->interger($number);
        }

        if($type === 'float')
        {
            return $this->dt->float($number);
        }

        if($type === 'text')
        {
            return $this->dt->string($number);
        }

        return $this->dt->currency($number, $type);
    }
}
