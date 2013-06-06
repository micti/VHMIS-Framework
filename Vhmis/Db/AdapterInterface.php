<?php

namespace Vhmis\Db;

interface AdapterInterface
{
    public function connect();

    public function isConnected();

    public function disconnect();

    public function getConnection();

    public function query($where);

    public function createStatement($sql = null, $parameters = null);

    public function lastValue();

    public function beginTransaction();

    public function commit($entity);

    public function rollback();
}
