<?php

class Vhmis_Benchmark
{
    protected $_timer = array();

    function timer($name)
    {
        $this->_timer[$name] = microtime();
    }

    function time($start, $stop)
    {
        if(!isset($this->_timer[$start]))
        {
            return '';
        }

        if(!isset($this->_timer[$stop]))
        {
            $this->_timer[$stop] = microtime();
        }

        list($s1m, $s1s) = explode(' ', $this->_timer[$start]);
		list($s2m, $s2s) = explode(' ', $this->_timer[$stop]);

		return number_format(($s2m + $s2s) - ($s1m + $s1s), 4, '.', ',');
    }
}