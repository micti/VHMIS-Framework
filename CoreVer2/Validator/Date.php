<?php

namespace Vhmis\Validator;

use Vhmis\Config\Configure;
use Vhmis\DateTime\DateTime;
use Vhmis\I18n\FormatPattern\DateTime as FormatDateTime;

/**
 * Kiểm tra ngày tháng
 *
 * Theo locale, theo format, trả về định dạng chuẩn ISO
 *
 * @author Micti
 */
class Date extends ValidatorAbstract
{

    /**
     * Locale
     *
     * @var string
     */
    protected $_locale;

    /**
     * Format ngày tháng để kiểm tra
     *
     * @var string
     */
    protected $_format;

    /**
     * Khởi tạo, 2 tham số locale và format có thể được truyền vào
     * Nếu không sẽ sử dụng mặt định sau này
     *
     * @param string $locale
     * @param string $format
     */
    public function __construct($locale = null, $format = null)
    {
        if (null !== $locale)
            $this->_locale = $locale;
        
        if (null !== $format)
            $this->_format = $format;
        
        if (!is_string($this->_locale)) {
            $this->_locale = Configure::get('Locale');
        }
        
        if (!is_string($this->_format)) {
            $this->_format = '';
        }
        
        parent::__construct();
    }

    public function isValid($value, $params = null)
    {
        if (!is_string($value) && !is_int($value) && !($value instanceof DateTime)) {
            $this->_setMessage('Ngày tháng không đúng kiểu', static::DATENOTTYPE, 'datenottype');
            return false;
        }
        
        if ($value instanceof DateTime) {
            $this->_standardValue = $value;
            return true;
        }
        
        if (is_string($value) || is_int($value)) {
            if ($this->_format === '')
                $this->_format = FormatDateTime::dateNativeFormat($this->_locale, 3);
            
            $date = (is_int($value)) ? new DateTime("@$value") : DateTime::createFromFormat($this->_format, $value);
            
            // Có một số ngày tháng sai nhưng được DateTime điều chỉnh lại cho
            // đúng, đối với trường hợp này
            // ta vẫn xem như là không hợp lệ
            $errors = DateTime::getLastErrors();
            if ($errors['warning_count'] > 0) {
                $this->_setMessage('Ngày tháng không hợp lệ', static::DATENOTVALID, 'datenotvalid');
                return false;
            }
            
            if ($date === false) {
                $this->_setMessage('Ngày tháng không hợp lệ', static::DATENOTVALID, 'datenotvalid');
                return false;
            }
            
            $this->_standardValue = $date;
        }
        
        return true;
    }
}