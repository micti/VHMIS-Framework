<?php

namespace VhmisTest\Container;


class Class1
{
    protected $hello = 'Hello';

    public function echoMe()
    {
        return '1';
    }

    public function helloInVietnamese()
    {
        $this->hello = 'Xin chao';
    }

    public function helloMe()
    {
        return $this->hello;
    }
}