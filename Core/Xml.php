<?php

class Vhmis_Xml
{
    /**
     * Tạo file XML data từ dữ liệu dạng mảng
     *
     * array(
     *    'country' => array(
     *        array(
     *            'name' => array(
     *                'full' => 'Vietnam',
     *                'short' => 'VN'
     *            ),
     *            'area' => '2345.354'
     *        ),
     *        array(
     *            'name' => array(
     *                'full' => 'Vietnam',
     *               'short' => 'VN'
     *            ),
     *           'area' => '2345.354'
     *         )
     *     )
     * );
     *
     * @param array $data Dữ liệu dạng mảng
     * @return string Nội dung dạng file xml
     */
    public static function fromArray($data, $root = 'data', $encoding = 'utf-8')
    {
        $xml = '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n";
        $xml .= '<' . $root . '>' . "\n";
        $xml .= self::_fromArray($data);
        $xml .= '</' . $root . '>' . "\n";

        return $xml;
    }

    /**
     * Phương thức trợ giúp tạo file XML data từ dữ liệu dạng mảng cho hàm simpleFromArray
     *
     * @param array $data Dữ liệu dạng mảng
     * @return string Nội dung dạng file xml
     */
    protected function _fromArray($data, $parentkey = '')
    {
        $xml = '';

        if(!isset($data[0])) // Phần tử đơn
        {
            if($parentkey != '') $xml .= '<' . $parentkey . '>' . "\n";

            foreach($data as $key => $data)
            {
                if(is_string($data)) $xml .= '<' . $key . '>' . $data . '</' . $key . '>' . "\n";
                else $xml .= self::_fromArray($data, $key);
            }

            if($parentkey != '') $xml .= '</' . $parentkey . '>' . "\n";
        }
        else // nhiều phần tử cùng cấp
        {
            foreach($data as $data)
            {
                if($parentkey != '') $xml .=  '<' . $parentkey . '>' . "\n";
                $xml .= self::_fromArray($data);
                if($parentkey != '') $xml .=  '</' . $parentkey . '>' . "\n";
            }
        }

        return $xml;
    }
}