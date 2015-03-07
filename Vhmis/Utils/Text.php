<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils;

/**
 * Text funtions
 */
class Text
{

    /**
     * Random string
     *
     * @param string $type
     * @param int $length
     *
     * @return string
     */
    public static function random($type, $length)
    {
        if ($type === 'digit') {
            $pattern = '0123456789';
        } elseif ($type === 'alpha') {
            $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } else {
            $type = 'alnum';
            $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $max = strlen($pattern);

        $rand = '';
        for ($i = 0; $i < $length; $i++) {
            // Lấy ngẫu nhiên một ký tự trong chuỗi pattern rồi đưa vào string ngẫu nhiên
            $rand .= $pattern[mt_rand(0, $max - 1)];
        }

        // Với type là số và chữ (alnum), yêu cầu có ít nhất 1 số và 1 chữ
        if ($type === 'alnum' and $length > 1) {
            if (ctype_alpha($rand)) {
                $rand[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
            } elseif (ctype_digit($rand)) {
                $rand[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
            }
        }

        return $rand;
    }

    /**
     * Convert encoding
     *
     * @param string $string
     * @param string $from
     * @param string $to
     * 
     * @return string
     */
    public static function convertEncoding($string, $from, $to)
    {
        $result = '';
        if (function_exists('iconv')) {
            $result = iconv($from, $to, $string);
        } elseif (function_exists('mb_convert_encoding')) {
            $result = mb_convert_encoding($string, $to, $from);
        }

        if ($result === false) {
            return '';
        }

        return $result;
    }

    static public function cleanFilename($filename)
    {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%22", // <
            "%3c", // <
            "%253c", // <
            "%3e", // >
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d"
        );

        // Spaces
        $space = array("%20");

        $filename = str_replace($bad, '', $filename);
        $filename = str_replace($space, ' ', $filename);
        $filename = preg_replace('/\s+/u', '_', $filename);

        return $filename;
    }
}
