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
     * Tìm theo primany key
     *
     * @param int $id
     * @return \Vhmis\Db\EntityInterface
     */
    public function findById($id);

    /**
     * Tìm theo các primany key
     *
     * @param array $ids
     * @return \Vhmis\Db\EntityInterface[]
     */
    public function findByIds($ids);

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

    /**
     * Lấy danh sách Ids liên quan
     *
     * @return array
     */
    public function getLastRelatedIds();

    public function update($where, $data = null);

    /**
     *
     * @param int $rowMod
     * @param int $setMod
     * @return \Vhmis\Db\ModelInterface
     */
    public function setFetchMod($rowMod, $setMod);

    /**
     *
     * @return \Vhmis\Db\ModelInterface
     */
    public function setDefaultFetchMod();

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
