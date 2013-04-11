<?php

namespace Vhmis\Collection;

/**
 * Class để tạo tập hợp quản lý các helpers của view
 *
 * $this->create('TextEscape');
 * $this->textEscape
 */
class ViewHelpers extends CollectionAbstract
{

    /**
     * Khởi tạo đối tượng View Helper
     *
     * @param string $class
     *            Tên class
     * @return Vhmis\View\Helper
     */
    public function create($class, $params = null)
    {
        $name = ___ctv($class);
        
        if (isset($this->$name))
            return $this->$name;
        
        $class = "Vhmis\\View\\Helper\\" . $class;
        
        $this->_collection[$name] = new $class();
        
        return $this->_collection[$name];
    }

    public function createFromI18nOutput($class, $params = null)
    {
        $name = ___ctv($class);
        
        if (isset($this->$name))
            return $this->$name;
        
        $class = "Vhmis\\I18n\\Output\\" . $class;
        
        $this->_collection[$name] = new $class();
        
        return $this->_collection[$name];
    }
}