<?php

class Vhmis_View_Helper_Text
{

    protected $_filter;

    public function __construct()
    {
        $this->_filter = new Vhmis_Filter();
    }

    public function bbcode($text)
    {
        $text = str_replace('[b]', '<b>', $text);
        $text = str_replace('[/b]', '</b>', $text);
        
        return $text;
    }

    public function htmlEntities($text)
    {
        return $this->_filter->htmlEntities($text);
    }

    public function addZero($text, $lenght)
    {
        return Vhmis_Utility_String::addZero($text, $lenght);
    }
}