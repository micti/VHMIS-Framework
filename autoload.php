<?php

if (class_exists('Vhmis\Application\Autoload')) {
    return;
}

include VHMIS_CORE_PATH . D_SPEC . 'Application' . D_SPEC . 'Autoload.php';

$auto = new Vhmis\Application\Autoload('Vhmis', VHMIS_PATH);
$auto->register();
