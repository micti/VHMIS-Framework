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
            $this->di->set('db' . ucfirst($db) . 'Connection', array(
                'class'  => '\\Vhmis\\Db\\' . $config['type'] . '\\Adapter',
                'params' => array(
                    array(
                        'type'  => 'param',
                        'value' => $config
                    )
                )
                ), true);
        }
    }

    /**
     * Lấy model
     *
     * Tên class đầy đủ của một Model trong ứng dụng có dạng \SystemName\Apps\AppName\Model\ModelName
     * Khi gọi phương thức lấy model thì tên truyền vào có dạng AppName\Model\ModelName
     *
     * @param type $model
     */
    public function getModel($model)
    {
        $modelPart = explode('\\', $model);

        $this->di->setOne($model, array(
            'class' => '\\' . SYSTEM . '\\Apps\\' . $model,
            'params' => array(
                array(
                    'type' => 'service',
                    'value' => 'db' . $modelPart[0] . 'Connection'
                )
            )
        ), true);

        return $this->di->get($model);
    }
}
