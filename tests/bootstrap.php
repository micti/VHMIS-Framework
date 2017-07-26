<?php

define('D_SPEC', DIRECTORY_SEPARATOR);

// Autoload framework
$root = realpath(dirname(__DIR__));
$vhmisCore = $root. DIRECTORY_SEPARATOR . 'Vhmis';
$rootTest = $root . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'VhmisTest';

include $vhmisCore . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'AutoloadPsr4.php';

$auto = new Vhmis\Application\AutoloadPsr4();
$auto->addNamespace('Vhmis', $vhmisCore);
$auto->addNamespace('VhmisTest', $rootTest);
$auto->register();
