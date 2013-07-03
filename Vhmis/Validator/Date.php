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
    protected $locale;

    /**
     * Format ngày tháng để kiểm tra
     *
     * @var string
     */
    protected $format;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        $this->locale = Configure::get('Locale') === null ? 'en_US' : Configure::get('Locale');
        $this->format = FormatDateTime::date($this->locale, 3);
    }

    /**
     * Thiết lập
     *
     * @param type $options
     * @return \Vhmis\Validator\ValidatorAbstract
     */
    public function setOptions($options)
    {
        if (isset($options['locale'])) {
            $this->locale = $options['locale'];
        }

        if (isset($options['format'])) {
            $this->format = $options['format'];
        } else {
            $this->format = FormatDateTime::dateNativeFormat($this->locale, 3);
        }

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

        if (!is_string($value) && !is_int($value) && !($value instanceof \DateTime)) {
            return false;
        }

        if ($value instanceof \DateTime) {
            $this->standardValue = $value;
            return true;
        }


        $date = (is_int($value)) ? new DateTime("@$value") : DateTime::createFromFormat($this->format, $value);

        // Có một số ngày tháng sai nhưng được DateTime điều chỉnh lại cho
        // đúng, đối với trường hợp này
        // ta vẫn xem như là không hợp lệ
        $errors = DateTime::getLastErrors();
        if ($errors['warning_count'] > 0) {
            return false;
        }

        if ($date === false) {
            return false;
        }

        $this->standardValue = $date;
        return true;
    }
}
