<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Config
 * @since Vhmis v2.0
 */

namespace Vhmis\Config;

/**
 * Class dùng để lấy các config của hệ thống
 *
 * @category Vhmis
 * @package Vhmis_Config
 */
class Config
{
    /**
     * Chứa dữ liệu config
     *
     * @var type
     */
    protected static $data;

    /**
     * Load config của hệ thống ứng dụng
     *
     * @param string $name
     * @param string $part Phần muồn lấy
     * @return array
     */
    public static function system($name, $part = '')
    {
        if (!isset(static::$data['system'][$name])) {
            $config = Load::filePhp(VHMIS_SYS_CONF_PATH . '/' . $name . '.php');

            if ($config === null || empty($config)) {
                echo 'No System Config : ' . $name;
                exit();
            }

            static::$data['system'][$name] = $config;
        } else {
            $config = static::$data['system'][$name];
        }

        if (is_string($part) && $part != '') {
            $parts = explode('/', $part);
            foreach ($parts as $p) {
                if (isset($config[$p])) {
                    $config = $config[$p];
                } else {
                    echo 'No Part : ' . $part . ' at System Config : ' . $name;
                    exit();
                }
            }
        }

        return $config;
    }

    /**
     * Load config của 1 ứng dụng
     *
     * @param string $app
     * @param string $part Phần muồn lấy
     * @param array
     */
    public static function app($app, $part = '')
    {
        if (!isset(static::$data['app'][$app])) {
            $applications = static::system('Global', 'app/list');

            if (isset($applications[$app]['ns'])) {
                $app = $applications[$app]['ns'];
            }

            $config = Load::filePhp(VHMIS_APPS_PATH . D_SPEC . $app . D_SPEC . 'Config' . D_SPEC . 'App.php');

            if ($config === null || empty($config)) {
                echo 'No App Config : ' . $app;
                exit();
            }

            static::$data['app'][$app] = $config;
        } else {
            $config = static::$data['app'][$app];
        }

        if (is_string($part) && $part != '') {
            $part = strtolower($part);
            $parts = explode('/', $part);
            foreach ($parts as $p) {
                if (isset($config[$p])) {
                    $config = $config[$p];
                } else {
                    echo 'No Part : ' . $part . ' at App Config : ' . $app;
                    exit();
                }
            }
        }

        return $config;
    }

    /**
     * Load config route của 1 ứng dụng
     *
     * @param string $app
     * @param array
     */
    public static function appRoutes($app)
    {
        return self::app($app, 'Routes');
    }

    /**
     * Load config service của 1 ứng dụng
     *
     * @param string $app
     * @param array
     */
    public static function appService($app)
    {
        return self::app($app, 'Service');
    }
}
