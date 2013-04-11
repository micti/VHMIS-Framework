<?php

define('D_SPEC', DIRECTORY_SEPARATOR);

require '../Vhmis/Application/Autoload.php';

$a = new \Vhmis\Application\Autoload('VhmisApp', '/fdfd/fdfd/System');

$a->load('VhmisApp'); echo '1' . '<br>';
$a->load('VhmisApp\\'); echo '1' . '<br>';
$a->load('VhmisApp\\Ha\\DSFfdfdfd'); echo '1' . '<br>';
$a->load('VhmisApp\\Ha1\\DSFfdfdfd'); echo '1' . '<br>';
$a->load('VhmisApp\\Ha44343\\DSFfdfdfd_ASFfdfdfd'); echo '1' . '<br>';