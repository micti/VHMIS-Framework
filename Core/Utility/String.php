<?php

/**
 * String
 *
 * Các vấn đề liên quan đến xử lý chuỗi
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright     Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link          https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category      VHMIS
 * @package       Utility
 * @subpackage    String
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 * Vhmis_Utility_String
 *
 * Lớp chứa các phương thức xử lý chuỗi
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright     Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link          https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category      VHMIS
 * @package       Utility
 * @subpackage    String
 * @since         1.0.0
 * @license       All rights reversed
 */
class Vhmis_Utility_String
{
    /**
     * Mã hóa chuỗi
     *
     * @param string $string Chuỗi cần mã hóa
     * @param string $salt Chuỗi chống phá mã hóa
     * @param string $method Phương thức mã hóa, mặc định là sha1
     */
    public static function hash($string, $salt1, $salt2, $method = null)
    {
        $string = $salt1 . $string . $salt2;

        $method = strtolower($method);

        if($method == null || $method == 'sha1')
        {
            return sha1($string);
        }

        return md5($string);
    }

    /**
     * Tạo chuỗi ngẫu nhiên
     *
     * @param string $type Kiểu của chuỗi ngẫu nhiên, mặc định là alnum gồm số với chữ
     * @param string $type Độ dài của chuỗi ngẫu nhiên
     * @return string Chuỗi ngẫu nhiên
     */
    public static function random($type, $length)
    {
        if($type = 'alnum')
        {
            $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        // Sẽ thêm nhiều type trong tương lai
        else {
            $type = 'alnum';
            $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $max = strlen($pattern);

        $rand = '';
		for($i = 0; $i < $length; $i++)
		{
			// Lấy ngẫu nhiên một ký tự trong chuỗi pattern rồi đưa vào string ngẫu nhiên
			$rand .= $pattern[mt_rand(0, $max - 1)];
		}

		// Với type là số và chữ (alnum), yêu cầu có ít nhất 1 số và 1 chữ
		if($type === 'alnum' AND $length > 1)
		{
		    // Nếu là chuỗi chữ thêm một ký tự số
			if(ctype_alpha($rand))
			{
				$rand[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
			}
			// Nếu là chuỗi số thì thêm ký tự chữ
			elseif(ctype_digit($rand))
			{
				$rand[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
			}
		}

		return $rand;
    }

    /**
     * Chuyển đổi tiếng việt có dấu sang không dấu
     *
     * @var string Chuỗi cần đổi
     * @return Chuỗi Tiếng Việt không còn dấu
     */
    public static function vietnameseToLatin($string)
    {
    }
}