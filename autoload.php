<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

if (class_exists('Vhmis\Application\Autoload')) {
    return;
}

include VHMIS_CORE_PATH . D_SPEC . 'Application' . D_SPEC . 'Autoload.php';

$auto = new Vhmis\Application\Autoload('Vhmis', VHMIS_PATH);
$auto->register();
