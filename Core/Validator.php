<?php

/**
 * Các loại cần kiểm tra
 *
 * 1. Không rỗng (rỗng là chuỗi rỗng hoặc chỉ gồm khoảng trắng, tab, xuống dòng ...)
 * 2. Alnum : chỉ bao gồm chữ và số
 * 3. Digit : chỉ bao gồm số
 * 4. Slug : chỉ bao gồm a-z,-,_
 * 5. Range : chỉ nằm trông một khoảng nào đó
 */

class Vhmis_Validator
{
    protected $_fitler;
    protected $_date;

    public function __construct($filter = null, $date = null)
    {
        if($filter == null)
        {
            $this->_filter = new Vhmis_Filter;
        }
        else $this->_filter = $filter;

        if($date == null)
        {
            $this->_date = new Vhmis_Date;
        }
        else $this->_date = $date;
    }

    /**
     * Hàm kiểm tra xem một chuỗi có phải là không rỗng hay rỗng
     */
    public function notEmpty($value)
    {
        return $this->_check('/[^\s]+/m', $value);
    }

    /**
     * Hàm kiểm tra xem độ dài của một chuỗi có nằm trong một vùng nào đó không
     */
    public function range($value, $min, $max)
    {
        // TODO : Unicode need check
        $len = mb_strlen($value);

        return ($len >= $min && $len <= $max);
    }

    /**
     * Hàm kiểm tra xem một số có nằm trong một vùng nào đó không
     */
    public function between($value, $min, $max)
    {
        //$value = $this->_filter->digit($value);

        return ($value >= $min && $value <= $max);
    }

    /**
     * Hàm kiểm tra xem có phải là 1 chuỗi gồm số và chữ cái hay không
     */
    public function alnum($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $new = $this->_filter->alnum($value, $allowWhiteSpace, $allowUnicode);

        if($new !== $value)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Hàm kiểm tra xem có phải là 1 chuỗi gồm chỉ chữ cái hay không
     */
    public function alpha($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $new = $this->_filter->alpha($value, $allowWhiteSpace, $allowUnicode);

        if($new !== $value)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Hàm kiểm tra xem một ngày có hợp lệ hay không
     */
    public function date($value, $format = 'dd/mm/yyyy', $returnISO = true, $allowEmpty = true)
    {
        if($format == 'dd/mm/yyyy')
        {
            // Nếu giá trị cần kiểm tra rỗng và cho phép rỗng
            if(trim($value) == '' && $allowEmpty === true)
            {
                return $returnISO ? '0000-00-00' : true;
            }

            $date = explode('/', $value, 3);

            if(count($date) != 3)
            {
                echo '12';
                return false;
            }

            if(count(trim($date[2])) <= 2)
            {
                $date = trim($date[2]) . '-' . trim($date[1]) . '-' . trim($date[0]);
            }
            else
            {
                $date = trim($date[2]) . '/' . trim($date[1]) . '/' . trim($date[0]);
            }

            if(!$this->_date->time($date))
            {
                echo '11';
                return false;
            }
            else
            {
                if($returnISO)
                {
                    return $date;
                }
                else
                {
                    return true;
                }
            }
        }

        /* TODO : need other format */
        return false;
    }

    protected function _check($pattern, $value)
    {
        if(preg_match($pattern, $value))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}