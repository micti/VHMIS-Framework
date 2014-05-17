<?php

namespace Vhmis\Benchmark;

class Benchmark
{

    protected $timer = array();

    public function timer($name)
    {
        $this->timer[$name] = microtime();
    }

    public function time($start, $stop)
    {
        if (!isset($this->timer[$start])) {
            return '';
        }

        if (!isset($this->timer[$stop])) {
            $this->timer[$stop] = microtime();
        }

        list ($s1m, $s1s) = explode(' ', $this->timer[$start]);
        list ($s2m, $s2s) = explode(' ', $this->timer[$stop]);

        return number_format(($s2m + $s2s) - ($s1m + $s1s), 4, '.', ',');
    }
}
