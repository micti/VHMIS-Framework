<?php

class Vhmis_Collection_Models extends Vhmis_Collection_Objects
{

    /**
     *
     * @param string $name Tên model
     * @param array|null $param Thông số khai báo
     * @return Vhmis_Model Đối tượng Vhmis_Model được gọi
     */
    public function load($name, $param = null)
    {
        $class = 'Vhmis_Model_' . $name;
        $name = ___ctv($name);
        if ($param == null) {
            $this->_loaded[$name] = new $class();
        } else {
            $this->_loaded[$name] = new $class($param);
        }
        
        return $this->_loaded[$name];
    }
}