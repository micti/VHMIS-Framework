<?php

class Vhmis_Utility_Vn
{

    public static function phoneNumber($number, $type = 'cell')
    {
        if (!is_numeric($number)) {
            return $number;
        }
        
        $number = (string) $number;
        
        // di động 10-11 số
        if ($type == 'cell') {
            $len = strlen($number);
            
            if ($len == 10) {
                return preg_replace("/([0-9]{4})([0-9]{3})([0-9]{3})/", "$1 $2 $3", $number);
            } else 
                if ($len == 11) {
                    return preg_replace("/([0-9]{5})([0-9]{3})([0-9]{3})/", "$1 $2 $3", $number);
                } else
                    return $number;
        }         

        // cố định 7 số cuối, 8 số cuối với hà nội sài gòn
        else 
            if ($type == 'fixed') {
                $len = strlen($number);
                
                // Xóa số 0 đầu nếu có
                if ($number[0] == '0')
                    $number = substr($number, 1);
                
                $len = strlen($number);
                
                // Hà nội, sài gòn
                if ($number[0] == '8' || $number[0] == '4') {
                    if ($len != 9) {
                        return $number;
                    } else {
                        return preg_replace("/([8|4]{1})([0-9]{8})/", "(0) $1 $2", $number);
                    }
                } else {
                    if ($len < 9 || $len > 10)
                        return $number;
                    
                    $last = substr($number, -7);
                    $first = substr($number, 0, ($len - 7));
                    return '(0) ' . $first . ' ' . $last;
                }
            }             

            // số đặc biệt
            else {
                return $number;
            }
    }
}