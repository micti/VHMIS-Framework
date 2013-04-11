<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Vhmis\Di;

/**
 * Class container, đối tượng chứa (quản lý) các services sẽ được injection vào
 */
class Di
{

    /**
     * Danh sách các services được đăng ký
     *
     * @var array
     */
    protected $_services = array();

    protected $_closure = array();

    protected $_definations = array();

    /**
     * Thông tin về class, param khi khởi tạo, các method (chủ yếu là setter)
     * cần thực thị
     *
     * @var array
     */
    protected $_classInfo = array();

    /**
     * Danh sách params khởi tạo cho từng class
     *
     * @var array
     */
    protected $_params = array();

    /**
     *
     * @param string $id            
     * @param object|\Closure $service            
     * @throws \Exception
     */
    public function set($id, $service, array $params = array())
    {
        if (!is_object($service)) {
            throw new \Exception("Not object");
        }
        
        if ($service instanceof \Closure) {
            $this->_closure[$id] = $service;
        } else {
            $this->_services[$id] = $service;
        }
    }

    public function get($id)
    {
        if (isset($this->_services[$id])) {
            return $this->_services[$id];
        }
        
        if (isset($this->_closure[$id])) {
            $this->_services[$id] = $this->_closure[$id]();
            return $this->_services[$id];
        }
        
        return null;
    }

    public function addParams($class, $name, $value)
    {
        $this->_params[$class][$name] = $value;
    }

    /**
     *
     * @param type $class            
     * @param type $oParams            
     * @return object
     */
    public function newInstance($class, $oParams)
    {
        $reflect = $this->_getReflect($class);
        $info = $this->_getClassInfo($class);
        
        if ($info['constructor'] == null)
            $object = $reflect->newInstanceWithoutConstructor();
        else {
            $object = $reflect->newInstanceArgs($this->_fillParams($info['constructor']['params'], $oParams));
        }
        
        return $object;
    }

    /**
     *
     * @param string $class            
     * @return \ReflectionClass
     */
    protected function _getReflect($class)
    {
        if (!isset($this->_classReflect[$class]))
            $this->_classReflect[$class] = new \ReflectionClass($class);
        
        return $this->_classReflect[$class];
    }

    /**
     *
     * @param string $class            
     * @return array
     */
    protected function _getClassInfo($class)
    {
        if (isset($this->_classInfo[$class]))
            return $this->_classInfo[$class];
        
        $reflect = $this->_getReflect($class);
        $constructor = $reflect->getConstructor();
        
        if ($constructor !== null) {
            $params = $constructor->getParameters();
            foreach ($params as $param) {
                if (array_key_exists($param->name, $this->_params[$class])) {
                    $this->_classInfo[$class]['constructor']['params'][$param->name] = $this->_params[$class][$param->name];
                } elseif ($param->isOptional()) {
                    $this->_classInfo[$class]['constructor']['params'][$param->name] = $param->getDefaultValue();
                } else {
                    $this->_classInfo[$class]['constructor']['params'][$param->name] = null;
                }
            }
        } else {
            $this->_classInfo[$class]['constructor'] = null;
        }
        
        return $this->_classInfo[$class];
    }

    /**
     * Điền giá trị vào cho $params
     *
     * @param type $params
     *            Danh sách params với giá trị mặc định (hoặc được cấu hình sẵn)
     * @param type $newParams
     *            Danh sách params với giá trị mới
     * @return array Danh sách params
     */
    protected function _fillParams($params, $newParams)
    {
        foreach ($newParams as $key => $value) {
            if (array_key_exists($key, $params)) {
                $val = $value;
            }
            
            $params[$key] = $val;
        }
        
        return $params;
    }
}
