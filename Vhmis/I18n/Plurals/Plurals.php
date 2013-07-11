<?php

namespace Vhmis\I18n\Plurals;

use \Vhmis\I18n\Resource\Resource as I18nResource;

class Plurals
{
    /**
     * Locale mặc định
     *
     * @var string
     */
    protected $locale;

    public function __construct()
    {
        // Locale mặc định
        $this->locale = 'vi_VN';
    }

    /**
     * Lấy plural type
     *
     * other|one|many|few|two
     *
     * @param int|double|float|string $number
     * @param string $locale
     * @return string
     */
    public static function type($number, $locale = '')
    {
        if (!is_numeric($number)) {
            return 'other';
        }

        $number = (double) $number;

        if ($locale === '')
            $locale = $this->locale;

        $rules = I18nResource::plurals($locale);

        foreach ($rules as $key => $rule) {
            $rule = strtolower($rule);

            // Split rules to many 'or' conditions
            // if there is one 'or' conditions get true
            // return type by key value
            $orCondtions = explode(' or ', $rule);
            foreach ($orCondtions as $orCondtion) {
                $andConditions = explode(' and ', $orCondtion);
                $orConditionResult = true;

                // 'and' condition is relation (within, is, is not, in, not in)
                // when one 'and' condition get false, its 'or' condition get false too
                foreach ($andConditions as $andCondition) {
                    if (strpos($andCondition, ' not in ') !== false) {
                        if (!static::caculateNotIn($number, $andCondition)) {
                            $orConditionResult = false;
                            break;
                        }
                    } else if (strpos($andCondition, ' within ') !== false) {
                        if (!static::caculateWithin($number, $andCondition)) {
                            $orConditionResult = false;
                            break;
                        }
                    } else if (strpos($andCondition, ' in ') !== false) {
                        if (!static::caculateIn($number, $andCondition)) {
                            $orConditionResult = false;
                            break;
                        }
                    } else if (strpos($andCondition, ' is not ') !== false) {
                        if (!static::caculateIsNot($number, $andCondition)) {
                            $orConditionResult = false;
                            break;
                        }
                    } else if (strpos($andCondition, ' is ') !== false) {
                        if (!static::caculateIs($number, $andCondition)) {
                            $orConditionResult = false;
                            break;
                        }
                    }
                }

                if ($orConditionResult) {
                    return str_replace('pluralRule-count-', '', $key);
                }
            }
        }

        return 'other';
    }

    /**
     * Xét quan hệ Is là đúng là hay sai
     *
     * @param double $number
     * @param string $syntax
     * @return boolean
     */
    protected static function caculateIs($number, $syntax)
    {
        list($math, $value) = explode(' is ', $syntax, 2);
        $math = str_replace(array('n', 'mod'), array('$number', '%'), $math);

        eval('$mathValue = ' . $math . ';');

        return ((double) $mathValue === (double) $value);
    }

    /**
     * Xét quan hệ Is Not là đúng là hay sai
     *
     * @param double $number
     * @param string $syntax
     * @return boolean
     */
    protected static function caculateIsNot($number, $syntax)
    {
        list($math, $value) = explode(' is not ', $syntax, 2);
        $math = str_replace(array('n', 'mod'), array('$number', '%'), $math);

        eval('$mathValue = ' . $math . ';');

        return ((double) $mathValue !== (double) $value);
    }

    /**
     * Xét quan hệ Not In là đúng hay là sai
     *
     * @param double $number
     * @param string $syntax
     * @return boolean
     */
    protected static function caculateNotIn($number, $syntax)
    {
        list($math, $list) = explode(' not in ', $syntax, 2);
        $math = str_replace(array('n', 'mod'), array('$number', '%'), $math);
        eval('$mathValue = ' . $math . ';');

        if (ceil($number) != floor($number)) {
            return true;
        }

        return (!static::isInList($mathValue, $list));
    }

    /**
     * Xét quan hệ In là đúng là hay sai
     *
     * @param double $number
     * @param string $syntax
     * @return boolean
     */
    protected static function caculateIn($number, $syntax)
    {
        list($math, $list) = explode(' in ', $syntax, 2);
        $math = str_replace(array('n', 'mod'), array('$number', '%'), $math);
        eval('$mathValue = ' . $math . ';');

        if (ceil($number) != floor($number)) {
            return false;
        }

        return (static::isInList($mathValue, $list));
    }

    /**
     * Xét quan hệ Within là đúng là hay sai
     *
     * @param double $number
     * @param string $syntax
     * @return boolean
     */
    protected static function caculateWithin($number, $syntax)
    {
        list($math, $list) = explode(' within ', $syntax, 2);
        $math = str_replace(array('n', 'mod'), array('$number', '%'), $math);

        eval('$mathValue = ' . $math . ';');

        return (static::isInList($mathValue, $list));
    }

    /**
     * Xét xem giá trị có nằm trong danh sách không
     *
     * Danh sách ở đây là danh theo định nghĩa của plural rules
     *
     * 1..3,5,7,9,10..11
     * 3,4,5
     *
     *
     * @param double $number
     * @param string $list
     * @return boolean
     */
    protected static function isInList($number, $list)
    {
        $list = explode(',', $list);

        foreach ($list as $l) {
            $value = explode('..', $l);
            $a = false;

            if (isset($value[1])) {
                $a = ((double) $value[0] <= (double) $number && (double) $number <= (double) $value[1]);
            } else {
                $a = ((double) $value[0] === (double) $number);
            }

            if ($a === true) {
                return true;
            }
        }

        return false;
    }
}
