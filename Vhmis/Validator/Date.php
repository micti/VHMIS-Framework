<?php

namespace Vhmis\Validator;

use Vhmis\DateTime\DateTime as VhmisDateTime;
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
    const E_NOT_DATE = 'notdate';
    const NOTVALID = 'notvalid';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::E_NOT_DATE => 'Không phải là ngày',
        self::NOTVALID => 'Ngày không hợp lệ'
    );

    /**
     * Locale
     *
     * @var string
     */
    protected $locale;

    /**
     * Format ngày tháng để kiểm tra
     *
     * @var string
     */
    protected $format;
    
    public function init()
    {    
        $this->locale = locale_get_default();
        $this->format = FormatDateTime::dateNativeFormat($this->locale, 3);
        
        return $this;
    }

    /**
     * Kiểm tra xem giá trị có phải là ngày tháng không (Có dựa theo locale)
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!is_string($value) && !($value instanceof \DateTime)) {
            $this->setMessage(self::E_NOT_DATE);
            return false;
        }

        if ($value instanceof \DateTime) {
            $this->standardValue = $value;
            return true;
        }

        $date = VhmisDateTime::createFromFormat($this->format, $value);

        // Có một số ngày tháng sai nhưng được DateTime điều chỉnh lại cho
        // đúng, đối với trường hợp này
        // ta vẫn xem như là không hợp lệ
        $errors = VhmisDateTime::getLastErrors();
        if ($errors['warning_count'] > 0) {
            $this->setMessage(self::NOTVALID);
            return false;
        }

        if ($date === false) {
            $this->setMessage(self::E_NOT_DATE);
            return false;
        }

        $this->standardValue = $date;
        return true;
    }
}
