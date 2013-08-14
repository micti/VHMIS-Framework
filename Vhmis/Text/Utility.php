<?php

namespace Vhmis\Text;

class Utility
{

    /**
     * Tìm chuỗi ngẫu nhiên
     *
     * @param string $type
     * @param int $length
     * @return string
     */
    public static function random($type, $length)
    {
        if ($type === 'alnum') {
            $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        } elseif ($type === 'digit') {
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
}
