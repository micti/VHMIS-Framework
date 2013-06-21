<?php

namespace Vhmis\Db;

interface ModelInterface
{
    public function setAdapter(AdapterInterface $adapter);

    public function init();

    public function findAll();

    /**
     *
     * @param int|array $id
     * @return \Vhmis\Db\EntityInterface|\Vhmis\Db\EntityInterface[]|array
     */
    public function findById($id);

    /**
     *
     * @param array $where
     * @param int $skip
     * @param int $limit
     * @return \Vhmis\Db\EntityInterface[]|array
     */
    public function find($where, $order, $skip, $limit);

    /**
     *
     * @param array $where
     * @return \Vhmis\Db\EntityInterface|array
     */
    public function findOne($where, $order);

    public function update($where, $data = null);

    public function insertQueue($entity);

    public function updateQueue($entity);

    public function deleteQueue($entity);

    public function flush();
}
