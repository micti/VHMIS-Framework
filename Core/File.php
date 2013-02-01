<?php

class Vhmis_File
{

    protected $_filename;

    protected $_directory;

    public function __construct($path, $create = false, $mode = '0755')
    {}

    public static function delete($file)
    {
        return @unlink($file);
    }

    public static function move($oldFile, $newFile)
    {
        return @rename($oldFile, $newFile);
    }
}