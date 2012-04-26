<?php

class Vhmis_View_Helper_Text
{
    public function bbcode($text)
    {
        $text = str_replace('[b]', '<b>', $text);
        $text = str_replace('[/b]', '</b>', $text);

        return $text;
    }
}