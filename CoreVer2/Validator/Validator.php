<?php

namespace Vhmis\Validator;

/**
 * Bộ kiểm tra dữ liệu
 */
class Validator extends ValidatorAbstract
{

    /**
     * Validator này sử dụng chung, dùng để kiểm tra nhiều thứ, nên hàm isValid
     * không được sử dụng
     * Kết quả luôn trả về là false
     *
     * @param type $value Giá trị cần kiểm tra
     * @param type $option
     * @return boolean
     */
    public function isValid($value, $option = null)
    {
        if (!is_array($option)) {
            $this->_setMessage('Không có thuộc tính kèm', 1, 'notoption');
            return false;
        }
        
        if (!isset($option['type']) || $option['type'] !== 'NotEmpty' || $option['type'] !== 'InRange' ||
             $option['type'] !== 'Regex') {
            $this->_setMessage('Không có kiểu kiểm tra', 2, 'nottype');
            return false;
        }
        
        if ($option['type'] == 'NotEmpty') {
            return $this->isNotEmpty($value);
        } elseif ($option['type'] == 'InRange') {
            if (!isset($option['max']) || !isset($option['min'])) {
                $this->_setMessage('Không đủ tham số', 3, 'notparam');
                return false;
            }
            
            return $this->isInRange($value, $option['min'], $option['max']);
        } elseif ($option['type'] == 'Regex') {
            if (!isset($option['regex'])) {
                $this->_setMessage('Không đủ tham số', 3, 'notparam');
                return false;
            }
            
            return $this->isRegex($value, $option['pattern']);
        }
        
        $this->_setMessage('', '', '');
        return false;
    }

    /**
     * Kiểm tra xem một chuỗi có phải là không rỗng hay không
     *
     * @param string $value Chuỗi cần kiểm tra
     * @return boolean
     */
    public function isNotEmpty($value)
    {
        $value = (string) $value;
        
        $result = $this->_isValidRegex($value, '/[^\s]+/m');
        
        if (false === $result) {
            $this->_setMessage('Giá trị nhập vào rỗng', 4, 'notvalid');
            return false;
        }
        
        return true;
    }

    /**
     * Kiểm tra giá trị có nằm trong một khoảng min max nào đó không
     *
     * @param string|float|int $value Giá trị cần kiểm tra
     * @param string|float|int $min Giá trị mốc dưới
     * @param string|float|int $max Giá trị mốc trên
     * @return boolean
     */
    public function isInRange($value, $min, $max)
    {
        $result = $value >= $min && $value <= $max;
        
        if (false === $result) {
            $this->_setMessage('Giá trị nhập vào không nằm trong khoảng %min% - %max%', 4, 'notvalid');
            return false;
        }
        
        return true;
    }

    /**
     * Kiểm tra giá trị có hợp với regex pattern không
     *
     * @param type $value Giá trị cần kiểm tra
     * @param type $pattern Regex
     * @return boolean
     */
    public function isRegex($value, $pattern)
    {
        $result = $this->_isValidRegex($value, $pattern);
        
        if (false === $result) {
            $this->_setMessage('Giá trị nhập vào không hợp lệ với %pattern%', 4, 'notvalid');
            return false;
        }
    }
}
