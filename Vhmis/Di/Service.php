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
 * Class service, dùng để biểu diễn 1 đối tượng được thêm vào Di
 */
class Service
{
    /**
     * Container chứa service
     *
     * @var \Vhmis\Di\Di
     */
    protected $di;
    protected $id;
    protected $service;
    protected $share;
    protected $instance;

    /**
     * Khởi tạo
     *
     * @param \Vhmis\Di\Di $di Container
     * @param string $id Tên được lưu trong container
     * @param mixed $service
     * @param boolean $share
     */
    public function __construct($di, $id, $service, $share)
    {
        $this->di = $di;
        $this->id = $id;
        $this->service = $service;
        $this->share = $share;
    }

    /**
     *
     * @return object
     */
    public function get()
    {
        if ($this->instance === null || $this->share === false) {
            if (is_object($this->service)) {
                if ($this->service instanceof \Closure) {
                    $this->instance = call_user_func($this->service);
                    return $this->instance;
                } else {
                    $this->instance = $this->service;
                    return $this->instance;
                }
            }

            if (is_string($this->service)) {
                if (class_exists($this->service)) {
                    $this->instance = new $this->service();
                    return $this->instance;
                }

                //throw new \Exception('Class ' . $this->service . ' not exist');
                return null;
            }

            if (is_array($this->service)) {
                if (!isset($this->service['class'])) {
                    //throw new \Exception('Must define class name by \'class\' index');
                    return null;
                }

                $class = $this->service['class'];

                if (!is_string($class) || !class_exists($class)) {
                    //throw new \Exception('Class ' . $class . ' not exist');
                    return null;
                }

                if (!isset($this->service['params']) || !is_array($this->service['params'])) {
                    $this->instance = new $class();
                    return $this->instance;
                }

                $params = $this->buildParams($this->service['params']);

                $this->instance = $this->di->newInstance($class, $params);
                return $this->instance;
            }
        }

        return $this->instance;
    }

    /**
     * Xử lý params ở nhiều dạng về params chuẩn để khởi tạo đối tượng
     *
     * @param array $params
     * @return array
     */
    protected function buildParams($params)
    {
        $buildParams = array();

        foreach ($params as $param) {
            if ($param['type'] == 'service') {
                $buildParams[] = $this->di->get($param['value']);
            } else if ($param['type'] == 'param') {
                $buildParams[] = $param['value'];
            } else {
                $buildParams[] = null;
            }
        }

        return $buildParams;
    }
}
