<?php
// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

define('D_SPEC', DIRECTORY_SEPARATOR);

require '../Vhmis/Cache/Adapter/StorageInterface.php';
require '../Vhmis/Cache/Adapter/Memcached.php';
require '../Vhmis/Cache/Adapter/File.php';
require '../Vhmis/Cache/AdapterFactory.php';

$cache = \Vhmis\Cache\AdapterFactory::fatory('Memcached', array(
    'persistent' => 'akaka'
));

$cache->addServer();

$cache->set('a', 1);

echo $cache->get('a');

$cache2 = \Vhmis\Cache\AdapterFactory::fatory('File', array(
    'path' => '/WebServer/www/t2j/Cache/Data'
));

$cache2->set('a', 1);

echo $cache2->get('a');