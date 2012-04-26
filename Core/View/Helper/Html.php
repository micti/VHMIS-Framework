<?php

class Vhmis_View_Helper_Html
{
    /**
     * Xuất ra thẻ select
     *
     * @param string $name Tên của thẻ
     * @param array $data Dữ liệu của select
     * @param string $value Giá trị chọn
     * @param array $options Các thuộc tính khác
     * @return Mã HTML tương ứng
     */
    public function select($name, $data, $value, $options)
    {
        $html = '<select name="' . $name . '"';

        if(isset($options['id'])) $html .= ' id="' . $options['id'] . '"';

        if(isset($options['onchange'])) $html .= ' onchange="' . $options['onchange'] . '"';

        if(isset($options['class'])) $html .= ' class="' . $options['class'] . '"';

        if(isset($options['style'])) $html .= ' style="' . $options['style'] . '"';

        $html .= '>' . "\n";

        if(isset($options['text']) && isset($options['value']))
        {
            foreach($data as $da)
            {
                $html .= '<option value="' . $da[$options['value']] . '"';
                if($value == $da[$options['value']]) $html .= ' selected="selected"';
                $html .= '>' . $da[$options['text']];
                $html .= '</option>' . "\n";
            }
        }
        else
        {
            foreach($data as $val => $txt)
            {
                $html .= '<option value="' . $val . '"';
                if($value == $val) $html .= ' selected="selected"';
                $html .= '>' . $txt;
                $html .= '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n";

        return $html;
    }
}