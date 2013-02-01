<?php

class Vhmis_View_Helper_Common
{

    /**
     * Lấy xưng hô của một người
     *
     * @param int $gender            
     * @param bool $teacher            
     */
    function personalTitle($gender, $teacher)
    {
        if ($gender == 1) {
            return $teacher ? 'Thầy' : 'Anh';
        } else {
            return $teacher ? 'Cô' : 'Chị';
        }
    }

    /**
     * Lấy giới tính
     *
     * @param
     *            int Mã giới tính
     */
    function gender($genderCode)
    {
        return $genderCode == 1 ? 'Nam' : 'Nữ';
    }

    /**
     * Lấy link avatar
     */
    function avatar($serverPath, $linkPath, $avatarPath, $default)
    {
        if ($avatarPath == '')
            return $default;
        
        $avatarPath = str_replace($serverPath, $linkPath, $avatarPath);
        
        // Sửa \ thành / nếu có
        return str_replace('\\', '/', $avatarPath);
    }
}