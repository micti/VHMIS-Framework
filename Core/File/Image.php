<?php

class Vhmis_File_Image
{
    protected $_lib = array('gd2');

    protected $_engine;

    public function __construct($path, $lib = 'gd2')
    {
        if(!in_array($lib, $this->_lib))
        {
            return false;
        }

        $engine = 'Vhmis_File_Image_' . ___fUpper($lib);
        $this->_engine = new $engine($path);
    }

    public function crop($size, $axis)
    {
        return $this->_engine->crop($size);
    }

    public function thumb($type, $size)
    {
        return $this->_engine->thumb($type, $size);
    }

    public function resize($size)
    {
        return $this->_engine->resize($size);
    }

    public function cropAndResize($size, $axis, $newSize)
    {
        return $this->_engine->cropAndResize($size, $axis, $newSize);
    }

    public function save($path, $clear = true)
    {
        return $this->_engine->save($path, $clear);
    }

    public function output($clear = true)
    {
        return $this->_engine->output($clear);
    }

    public function isError()
    {
        return $this->_engine->isError();
    }
}