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
 * Class container, đối tượng chứa các services
 */
class Di
{

    /**
     * Danh sách các services được đăng ký
     *
     * @var \Vhmis\Di\Service[]
     */
    protected $services = array();

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
     * Gán service vào
     *
     * @param string $id
     * @param mixed $service
     * @param boolean $share
     * @return \Vhmis\Di\Serivce
     */
    public function set($id, $service, $share = false)
    {
        if($share !== false) {
            $share = true;
        }

        $this->services[$id] = new Service($this, $service, $share);

        return $this->services[$id];
    }

    /**
     * Gán service vào nếu id của nó chưa được sử dụng
     *
     * @param string $id
     * @param mixed $service
     * @param boolean $share
     * @return \Vhmis\Di\Serivce
     */
    public function setOne($id, $service, $share = false)
    {
        if(array_key_exists($id, $this->services))
        {
            return $this->services['id'];
        }

        return $this->set($id, $service, $share);
    }

    /**
     * Lấy đối tượng đã được thiết lập
     *
     * Trong trường hợp đối tượng chưa được thiết lập, nếu truyền vào $id chính là tên class thì đối tượng sẽ được
     * tạo tự động
     *
     * @param string $id
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function get($id, $params = null)
    {
        if(isset($this->services[$id]))
        {
            return $this->services[$id]->get($params);
        }

        if(class_exists($id)) {
            $this->set($id, $id, true);
            return $this->services[$id]->get($params);
        }

        throw new \Exception('Service ' . $id . ' not exist.');
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
                if (isset($this->_params[$class]) && array_key_exists($param->name, $this->_params[$class])) {
                    $this->_classInfo[$class]['constructor']['params'][] = $this->_params[$class][$param->name];
                } elseif ($param->isOptional()) {
                    $this->_classInfo[$class]['constructor']['params'][] = $param->getDefaultValue();
                } else {
                    $this->_classInfo[$class]['constructor']['params'][] = null;
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
     * @param type $params Danh sách params với giá trị mặc định (hoặc được cấu hình sẵn)
     * @param type $newParams Danh sách params với giá trị mới
     * @return array Danh sách params
     */
    protected function _fillParams($params, $newParams)
    {
        foreach ($newParams as $key => $value) {
            if (array_key_exists($key, $params)) {
                $val = $value;
                $params[$key] = $val;
            }
        }

        return $params;
    }
}
