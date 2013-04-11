<?php

namespace Vhmis\View\Helper\Html;

/**
 * Abstract class cho việc tạo mã HTML tag
 */
abstract class HtmlAbstract
{

    /**
     * Danh sách các thuộc tính bình thường và giá trị mặc định
     *
     * @var array
     */
    protected $_normalAttributes = array(
        'id' => '',
        'name' => ''
    );

    /**
     * Danh sách các thuộc tính nhận giá trị tương tứng có/không
     *
     * @var array
     */
    protected $_booleanAttributes = array(
        'autocomplete' => array(
            'off',
            'on'
        ),
        'autofocus' => array(
            '',
            'autofocus'
        ),
        'checked' => array(
            '',
            'checked'
        ),
        'disabled' => array(
            '',
            'disabled'
        ),
        'multiple' => array(
            '',
            'multiple'
        ),
        'readonly' => array(
            '',
            'readonly'
        ),
        'required' => array(
            '',
            'required'
        ),
        'selected' => array(
            '',
            'selected'
        )
    );

    /**
     * Gọi thực thi
     */
    abstract public function __invoke();

    /**
     * Tạo chuỗi các thuộc tính từ mảng dữ liệu
     *
     * @param array $attributes
     * @return string
     */
    protected function _attribute($attributes)
    {
        $attribute = array();
        
        foreach ($attributes as $attr => $value) {
            $attr = strtolower($attr);
            
            if (isset($this->_booleanAttributes[$attr])) {
                $value = $this->_booleanAttribute($attr, $value);
                
                if ($value === '')
                    continue;
                else
                    $attribute[] = $attr . ' = "' . $value . '"';
            } elseif (isset($this->_normalAttributes[$attr])) {
                $attribute[] = $attr . ' = "' . $value . '"';
            } else {
                if (substr($attr, 0, 5) === 'data-') {
                    $attribute[] = $attr . ' = "' . $value . '"';
                }
            }
        }
        
        return implode(' ', $attribute);
    }

    /**
     * Lấy giá trị của các thuộc tính có/không
     *
     * @param string $attr
     * @param bool $value
     * @return string
     */
    protected function _booleanAttribute($attr, $value)
    {
        if (!is_bool($value) && in_array($value, $this->_booleanAttributes[$attr])) {
            return $value;
        }
        
        $value = (bool) $value;
        return ($value ? $this->_booleanAttributes[$attr][1] : $this->_booleanAttributes[$attr][0]);
    }
}