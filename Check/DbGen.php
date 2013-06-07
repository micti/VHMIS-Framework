<?php

require '../Vhmis/Db/AdapterInterface.php';
require '../Vhmis/Db/ModelInterface.php';
require '../Vhmis/Db/MySQL/Adapter.php';
require '../Vhmis/Db/MySQL/Statement.php';
require '../Vhmis/Db/MySQL/Result.php';
require '../Vhmis/Db/MySQL/Model.php';
require '../Vhmis/Db/MySQL/Entity.php';
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

$build = new Db\BuildModel($config, 'VhmisSystem\Apps\System\Model', '/WebServer/www/t2j/Cache/');

$build->build('viethanit_system');

/*$config = array(
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '123',
    'db' => 'viethanit_hrm',
    'auto' => true
);

$db = new Db\Adapter($config);
$model = new \VhmisApp\Nhansu\Model\HrmNhansu();

$model->setAdapter($db);
$model->init();*/
//var_dump($model->findAll());
//var_dump($model->findById(205));
//var_dump($model->findById("a"));
//var_dump($model->update(array('hoatdong' => '0'), array('hoatdong' => '1')));
//var_dump($model->find(array('ngay_vao_truong' => '2009-04-17')));

/*$entity1 = new VhmisApp\Nhansu\Model\HrmNhansu\Entity();
$entity1->setTen('Nhat Anh');
$entity1->setTenHo('Le');

$model->insertQueue($entity1);

$entity2 = new VhmisApp\Nhansu\Model\HrmNhansu\Entity();
$entity2->setTen('Nhat Anh');
$entity2->setTenHo('Le');

$model->insertQueue($entity2);

$model->flush();*/

/*$entity = $model->findById(324);

echo $entity->getId();

$entity->setMa('AAAAAAAA1AAA');

var_dump($entity->isChanged());

$model->deleteQueue($entity);

$model->flush();

var_dump($entity->isDeleted());

var_dump($model);*/
