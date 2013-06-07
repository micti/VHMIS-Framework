<?php

namespace Vhmis\Db;

interface ModelInterface
{
    public function setAdapter(AdapterInterface $adapter);

    public function init();

    public function findAll();

    public function findById($id);

    public function find($where, $skip, $limit);

    public function findOne($where);

    public function update($where, $data = null);

    public function insertQueue($entity);

    public function updateQueue($entity);

    public function deleteQueue($entity);

    public function flush();
}
