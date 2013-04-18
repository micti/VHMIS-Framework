<?php

require '../Vhmis/Db/MySQL/Adapter.php';
require '../Vhmis/Db/MySQL/Statement.php';
require '../Vhmis/Db/MySQL/Result.php';
require '../Vhmis/Db/MySQL/Model.php';
require '../Vhmis/Db/MySQL/BuildModel.php';
require 'Model.php';
require 'ModelEntity.php';

use \Vhmis\Db\MySQL as Db;

$config = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '123',
    'auto' => true
);

$build = new Db\BuildModel($config, 'VhmisApp\Nhansu\Model', '/WebServer/www/t2j/Cache/');

//$build->build('viethanit_hrm');

$config = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '123',
    'db' => 'viethanit_hrm',
    'auto' => true
);

$db = new Db\Adapter($config);
$model = new \VhmisApp\Nhansu\Model\HrmNhansu();

$model->setAdapter($db);
$model->init();
//var_dump($model->findAll());
var_dump($model->findById(205));
var_dump($model->findById("a"));
