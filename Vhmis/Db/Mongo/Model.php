<?php

namespace Vhmis\Db\Mongo;

use \Vhmis\Db\AdapterInterface;
use \Vhmis\Db\ModelInterface;

class Model implements ModelInterface
{
    /**
     * Tên class Model (bắt đầu bằng \)
     *
     * @var string
     */
    protected $class;

    /**
     * Tên class Entity
     *
     * @var string
     */
    protected $entityClass;

    /**
     * Tên collection model
     *
     * @var string
     */
    protected $collection;

    /**
     * Adapter
     *
     * @var \Vhmis\Db\Mongo\Adapter
     */
    protected $adapter = null;

    /**
     * Collection ứng với model
     *
     * @var \MongoCollection
     */
    protected $mongoCollection;

    /**
     * Tên trường primary key
     *
     * @var string
     */
    protected $idKey = '_id';

    /**
     * Danh sách các key của Entity chờ cập nhật (thêm, xóa, sửa) lên CSDL
     *
     * @var array
     */
    protected $entityKey = array();

    /**
     * Danh sách các Entity chờ được insert
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityInsert = array();

    /**
     * Danh sách các Entity chờ được update
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityUpdate = array();

    /**
     * Danh sách các Entity chờ được delete
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityDelete = array();

    /**
     * Danh sách các Entity đã được insert vào CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityHasInserted = array();

    /**
     * Danh sách các Entity đã được update lên CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityHasUpdated = array();

    /**
     * Danh sách các Entity đã được delete khỏi CSDL
     * Dùng trong quá trình rollback nếu có lỗi xảy ra trong toàn bố quá trình cập nhật CSDL
     *
     * @var \Vhmis\Db\Mongo\Entity[]
     */
    protected $entityHasDeleted = array();

    /**
     * Khởi tạo
     *
     * @param \Vhmis\Db\AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        if ($adapter instanceof Adapter) {
            $this->setAdapter($adapter);
        }
    }

    /**
     * Thiết lập adapter
     *
     * @param \Vhmis\Db\MySQL\Adapter $adapter
     * @return \Vhmis\Db\MySQL\Model
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this->init();
    }

    /**
     * Khởi tạo model sau khi thiết lập adapter thành công
     *
     * @return \Vhmis\Db\MySQL\Model
     * @throws \Exception
     */
    public function init()
    {
        if (!$this->adapter instanceof Adapter) {
            throw new \Exception('Need an Adapter');
        }

        if ($this->collection == '') {
            $this->collection = $this->camelCaseToUnderscore($table);
        }

        $this->mongoCollection = $this->adapter->getConnection()->selectCollection($this->collection);

        $this->class = '\\' . get_class($this);

        $class = explode('\\', $this->class);
        $collection = $class[count($class) - 1];

        $class[count($class) - 1] = $collection . 'Entity';

        $this->entityClass = implode('\\', $class);

        return $this;
    }

    /**
     * Tìm tất cả dữ liệu
     *
     * - Nếu bảng chưa có giá trị thì sẽ trả về mảng rỗng
     * - Nếu bảng đã có dữ liệu thì sẽ trả về mảng chứa các đối tượng Entity tương ứng với Model
     *
     * @return array
     */
    public function findAll()
    {
        $cursor = $this->mongoCollection->find();

        foreach ($cursor as $document) {
            $data[] = $this->fillRowToEntityClass($document);
        }

        return $data;
    }

    /**
     * Tìm theo primany key
     *
     * @param string $id
     * @return null
     */
    public function findById($id)
    {
        $document = $this->mongoCollection->findOne(array('_id' => $id));

        if ($document !== null) {
            return $this->fillRowToEntityClass($document);
        }

        return null;
    }

    /**
     * Tìm theo các primany key
     *
     * @param array $ids
     * @return \Vhmis\Db\MySQL\Entity[]
     */
    public function findByIds($ids)
    {
        //if (!is_array($ids) || empty($ids)) {
        //return array();
        //}
        //return $this->find(array(array($this->idKey, 'in', $ids)));
    }

    /**
     * Tìm
     *
     * @param array $where Mảng chứa điều kiện tìm kiếm
     * @param array $order Mảng chứa điều kiện sắp xếp
     * @param int $skip Số row bỏ qua
     * @param int $limit Số row lấy
     * @return \Vhmis\Db\MySQL\Entity[]
     * @throws \Exception
     */
    public function find($where = array(), $order = array(), $skip = 0, $limit = 0)
    {

        $cursor = $this->mongoCollection->find($where);

        if (is_array($order)) {
            $newOrder = array();
            foreach ($order as $field => $value) {
                $value = strval($value);
                if ($value === '1' || $value === 'asc') {
                    $newOrder[$field] = 1;
                } elseif ($value === '-1' || $value === 'desc') {
                    $newOrder[$field] = -1;
                }
            }
            $cursor->sort($newOrder);
        }

        $cursor->skip($skip)->limit($limit);

        $data = array();

        foreach ($cursor as $document) {
            $data[] = $this->fillRowToEntityClass($document);
        }

        return $data;
    }

    public function findOne($where, $order = array())
    {
        $document = $this->mongoCollection->findOne();

        if ($document !== null) {
            return $this->fillRowToEntityClass($document);
        }

        return null;
    }

    /**
     * Lấy danh sách Ids liên quan
     *
     * @return array
     */
    public function getLastRelatedIds() {
        return array();
    }

    /**
     *
     * @param \Vhmis\Db\Mongo\Entity $entity
     */
    public function insert($entity)
    {
        $data = $entity->insertSQL();

        if ($data === array()) {
            return false;
        }

        $result = $this->mongoCollection->insert($data, array('w' => 1));

        if ($result['ok'] == 1) {
            $entity->_id = $data['_id'];
            $entity->setNewValue();
            return true;
        }

        return false;
    }

    /**
     *
     * @param \Vhmis\Db\Mongo\Entity $entity
     */
    public function update($entity, $a = null)
    {
        if (!$entity->isChanged()) {
            return true;
        }

        $data = $entity->updateSQL();

        if ($data === array()) {
            return false;
        }

        $result = $this->mongoCollection->update(array('_id' => new \MongoId($entity->_id)), $data);

        if ($result['ok'] == 1) {
            $entity->setNewValue();
            return true;
        }

        return false;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần insert vào CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     * @return \Vhmis\Db\MySQL\Model
     */
    public function insertQueue($entity)
    {

    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần update lên CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     * @return \Vhmis\Db\MySQL\Model
     */
    public function updateQueue($entity)
    {

    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần delete khỏi CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     * @return \Vhmis\Db\MySQL\Model
     */
    public function deleteQueue($entity)
    {

    }

    /**
     * Thực hiện các thay đổi trên CSDL
     *
     * @return boolean
     */
    public function flush()
    {

    }

    /**
     * Thực hiện việc insert các entity vào CSDL
     */
    protected function doInsert()
    {

    }

    /**
     * Thực hiện việc update các entity lên CSDL
     */
    protected function doUpdate()
    {

    }

    /**
     * Thực hiện việc delete các entity khỏi CSDL
     */
    protected function doDelete()
    {

    }

    /**
     * Phục hồi các entity đã được insert lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackInsert()
    {

    }

    /**
     * Phục hồi các entity đã được update lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackUpdate()
    {

    }

    /**
     * Phục hồi các entity đã được xóa lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackDelete()
    {

    }

    public function setFetchMod($rowMod, $setMod)
    {
        return $this;
    }

    public function setDefaultFetchMod()
    {
        return $this;
    }

    /**
     * Tạo một đối tượng Entity từ một kết quả trả về ở cơ sở dữ liệu
     *
     * @param array $row
     * @return
     */
    protected function fillRowToEntityClass($row)
    {
        $entity = new $this->entityClass($row);

        return $entity;
    }
}
