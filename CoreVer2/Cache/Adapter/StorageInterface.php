<?php

namespace Vhmis\Cache\Adapter;

/**
 *
 * @author Micti
 */
interface StorageInterface
{
    public function get($id);

    public function set($id, $value);

    public function remove($id);

    public function removeAll();
}
