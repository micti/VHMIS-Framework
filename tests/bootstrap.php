<?php

define('D_SPEC', DIRECTORY_SEPARATOR);

// Autoload framework
$root = realpath(dirname(__DIR__));
$vhmisCore = $root. DIRECTORY_SEPARATOR . 'Vhmis';

include $vhmisCore . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Autoload.php';

$auto = new Vhmis\Application\Autoload('Vhmis', $root);
$auto->register();
