<?php

namespace Vhmis\Di;

use Vhmis\Config\Config;

/**
 * class dùng để quản lý các dịch vụ
 *
 */
class ServiceManager implements DiAwareInterface
{
    /**
     *
     * @var \Vhmis\Di\Di
     */
    protected $di;

    /**
     * Thiết lập Di
     *
     * @param \Vhmis\Di\Di $di
     */
    public function setDi(Di $di)
    {
        $this->di = $di;
    }

    public function set($name, $service, $share)
    {
        return $this->di->set($name, $service, $share);
    }

    public function get($name, $params = null)
    {
        return $this->di->get($name, $params);
    }

    /**
     * Khai báo hết các connection thông qua khởi tạo các Db Adapter từ file config db của hệ thống
     *
     * Mỗi app về nguyên tắc sẽ có một db, tên index khai báo db của app nào thì trung với tên app của app đó
     */
    public function setConnections()
    {
        $configDatabase = Config::system('Database');

        // Khai báo các kết nối database
        foreach ($configDatabase as $db => $config) {
            $this->di->set('db' . $db . 'Connection', array(
                'class'  => '\\Vhmis\\Db\\' . $config['type'] . '\\Adapter',
                'params' => array(
                    array(
                        'type'  => 'param',
                        'value' => $config
                    )
                )
            ), true);
            
            $this->di->set('db' . $db, [
                'class'  => '\\Vhmis\\Db\\' . $config['type'] . '\\Db',
                'params' => [
                    [
                        'type'  => 'service',
                        'value' => 'db' . $db . 'Connection'
                    ]
                ]
            ]);
        }
    }

    /**
     * Lấy model
     *
     * @return \Vhmis\Db\ModelInterface
     */
    public function getModel($app, $model = null)
    {
        if ($model === null) {
            $modelPart = explode('\\', $app);
            $app = $modelPart[0];
            $model = $modelPart[2];
        }

        $this->di->setOne($model, array(
            'class'  => '\\' . SYSTEM . '\\Apps\\' . $app . '\\Model\\' . $model,
            'params' => array(
                array(
                    'type'  => 'service',
                    'value' => 'db' . $app . 'Connection'
                )
            )
        ), true);

        return $this->di->get($model);
    }

    /**
     *
     * @param string $name
     * @return \Vhmis\Session\Session
     */
    public function getSession($name)
    {
        $this->di->setOne($name . 'Session', array(
            'class'  => '\\Vhmis\\System\\Session',
            'params' => array(
                array(
                    'type'  => 'param',
                    'value' => $name
                )
            )
        ), true);

        return $this->di->get($name . 'Session');
    }

    /**
     * Lấy validator
     *
     * @return \Vhmis\Validator\Validator
     */
    public function getValidator()
    {
        return $this->get('\Vhmis\Validator\Validator');
    }
}
