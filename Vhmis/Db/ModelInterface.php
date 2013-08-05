<?php

namespace Vhmis\Db;

interface ModelInterface
{
    public function setAdapter(AdapterInterface $adapter);

    public function init();

    /**
     * Tìm tất cả
     *
     * @return \Vhmis\Db\EntityInterface[]
     */
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

    /**
     *
     * @param \Vhmis\Db\EntityInterface $entity
     * @return \Vhmis\Db\ModelInterface
     */
    public function insertQueue($entity);

    /**
     *
     * @param \Vhmis\Db\EntityInterface $entity
     * @return \Vhmis\Db\ModelInterface
     */
    public function updateQueue($entity);

    /**
     *
     * @param \Vhmis\Db\EntityInterface $entity
     * @return \Vhmis\Db\ModelInterface
     */
    public function deleteQueue($entity);

    /**
     *
     * @return bool
     */
    public function flush();
}
