<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Vhmis\Di;

interface ServiceManagerAwareInterface
{
    /**
     * Thiết lập Service Manager
     *
     * @param \Vhmis\Di\ServiceManager $di
     */
    public function setServiceManager(ServiceManager $sm);
}
