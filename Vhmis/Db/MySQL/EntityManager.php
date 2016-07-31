<?php

namespace Vhmis\Db\MySQL;

class EntityManager
{
    /**
     * Danh sách các key của Entity chờ cập nhật (thêm, xóa, sửa) lên CSDL
     *
     * @var array
     */
    protected $entityKey = [];

    /**
     * Danh sách các Entity chờ được insert
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityForInsert = [];

    /**
     * Danh sách các Entity chờ được update
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityForUpdate = [];

    /**
     * Danh sách các Entity chờ được delete
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityForDelete = [];

    /**
     * Danh sách các Entity đã được insert vào CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityHasInserted = [];

    /**
     * Danh sách các Entity đã được update lên CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityHasUpdated = [];

    /**
     * Danh sách các Entity đã được delete khỏi CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityHasDeleted = [];
    
    protected $db;

    public function __construct($db)
    {
        if (!($db instanceof Db)) {
            throw new \Exception('No Db for Work');
        }
        
        $this->db = $db;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần insert vào CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function forInsert($entity)
    {
        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityForInsert[$id] = $entity;

        return $this;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần update lên CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function forUpdate($entity)
    {
        
//        if ($entity->{$entity->idKey} === null) {
//            return $this;
//        }

        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        if ($entity->isChanged() === false) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityForUpdate[$id] = $entity;

        return $this;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần delete khỏi CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function forDelete($entity)
    {

//        if ($entity->{$entity->idKey} === null) {
//            return $this;
//        }

        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityForDelete[$id] = $entity;

        return $this;
    }

    /**
     * Thực hiện các thay đổi trên CSDL
     *
     * @return boolean
     */
    public function flush()
    {
        if (empty($this->entityKey)) {
            return true;
        }

        $this->db->getAdapter()->beginTransaction();

        try {
            $this->doInsert();
            $this->doUpdate();
            $this->doDelete();

            $this->db->getAdapter()->commit();

            $this->entityHasInserted = [];
            $this->entityHasUpdated = [];
            $this->entityHasDeleted = [];

            return true;
        } catch (\PDOException $e) {
            $this->db->getAdapter()->rollback();
            $this->rollback();

            return false;
        }
    }

    /**
     * Thực hiện việc insert các entity vào CSDL
     */
    protected function doInsert()
    {
        foreach ($this->entityForInsert as $id => $entity) {
            $entity->insert();
            $this->entityHasInserted[$id] = $entity;

            unset($this->entityKey[$id]);
            unset($this->entityForInsert[$id]);
        }
    }

    /**
     * Thực hiện việc update các entity lên CSDL
     */
    protected function doUpdate()
    {
        foreach ($this->entityForUpdate as $id => $entity) {
            $entity->update();

            $this->entityHasUpdated[$id] = $entity;

            unset($this->entityKey[$id]);
            unset($this->entityForUpdate[$id]);
        }
    }

    /**
     * Thực hiện việc delete các entity khỏi CSDL
     */
    protected function doDelete()
    {
        foreach ($this->entityForDelete as $id => $entity) {
            $entity->delete();
            $this->entityHasDeleted[$id] = $entity;
            unset($this->entityKey[$id]);
            unset($this->entityForDelete[$id]);
        }
    }

    /**
     * Phục hồi các entity về các trạng thái trước khi cập nhật vào dữ liệu
     */
    protected function rollback()
    {
        foreach ($this->entityHasInserted as $id => $entity) {
            $entity->rollback();

            unset($this->entityHasInserted[$id]);
        }
    
        foreach ($this->entityHasUpdated as $id => $entity) {
            $entity->rollback();

            unset($this->entityHasUpdated[$id]);
        }
    
        foreach ($this->entityHasDeleted as $id => $entity) {
            $entity->setDeleted(false);

            unset($this->entityHasDeleted[$id]);
        }
    }
}
