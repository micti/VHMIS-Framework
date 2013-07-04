<?php

namespace Vhmis\Validator;

use Vhmis\DateTime\DateTime;

/**
 * Kiểm tra ngày tháng
 *
 * Theo locale, theo format, trả về định dạng chuẩn ISO
 *
 * @author Micti
 */
class Time extends ValidatorAbstract
{
    const NOTTIME = 'nottime';

    /**
     * Các thông báo lỗi
     *
     * @var array
     */
    protected $messages = array(
        self::NOTTIME => 'Không phải là thời gian'
    );

    /**
     * Có kèm theo giây hay không
     *
     * @var boolean
     */
    protected $nosecond = true;

    /**
     * Khởi tạo
     */
    public function __construct()
    {
        $this->nosecond = true;
    }

    /**
     * Thiết lập
     *
     * @param type $options
     * @return \Vhmis\Validator\ValidatorAbstract
     */
    public function setOptions($options)
    {
        if (isset($options['nosecond'])) {
            $this->nosecond = $options['nosecond'] === true ? true : false;
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

        if (!is_string($value)) {
            $this->setMessage(self::NOTTIME);
            return false;
        }

        if($this->nosecond) {
            $date = DateTime::createFromFormat('G:i:s', $value . ':00');
        } else {
            $date = DateTime::createFromFormat('G:i:s', $value);
        }

        // Có một số ngày tháng sai nhưng được DateTime điều chỉnh lại cho
        // đúng, đối với trường hợp này
        // ta vẫn xem như là không hợp lệ
        $errors = DateTime::getLastErrors();
        if ($errors['warning_count'] > 0) {
            $this->setMessage(self::NOTTIME);
            return false;
        }

        if ($date === false) {
            $this->setMessage(self::NOTTIME);
            return false;
        }

        $this->standardValue = $date->format('H:i:s');
        return true;
    }
}
