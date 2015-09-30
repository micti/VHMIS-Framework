<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils;

/**
 * Text, string... helper functions
 */
class Text
{

    /**
     * Random string
     *
     * @param string $type
     * @param int    $length
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

    public static function cleanFilename($filename)
    {
        $bad = [
            "<!--", "-->", "'", "<", ">",
            '"', '&', '$', '=', ';',
            '?', '/',
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
        ];

        // Spaces
        $space = ["%20"];

        $filename = str_replace($bad, '', $filename);
        $filename = str_replace($space, ' ', $filename);
        $filename = preg_replace('/\s+/u', '_', $filename);

        return $filename;
    }

    /**
     * Camelcase to underscore.
     *
     * @param string $string
     *
     * @return string
     */
    public static function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    /**
     * Underscore to camelcase.
     *
     * @param string $string
     * @param bool   $upperFirstWord
     *
     * @return string
     */
    public static function underscoreToCamelCase($string, $upperFirstWord = false)
    {
        $parts = explode('_', $string);
        $parts = $parts ? array_map('ucfirst', $parts) : [$string];
        $parts[0] = $upperFirstWord ? ucfirst($parts[0]) : lcfirst($parts[0]);

        return implode('', $parts);
    }
}
