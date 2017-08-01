<?php

namespace Vhmis\Db\MySQL;

use \Vhmis\Db\AdapterInterface;
use \Vhmis\Db\ModelInterface;
use Vhmis\Utils\Text;

class Model implements ModelInterface
{
    const FETCH_MOD_ROW_ENTITY = 0;
    const FETCH_MOD_ROW_ARRAY = 1;
    const FETCH_MOD_SET_ARRAY = 0;
    const FETCH_MOD_SET_IDARRAY = 1;

    /**
     * Model class name
     *
     * @var string
     */
    protected $class;

    /**
     * Entity class name
     *
     * @var string
     */
    protected $entityClass;

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Adapter
     *
     * @var \Vhmis\Db\MySQL\Adapter
     */
    protected $adapter = null;

    /**
     * Primary key
     *
     * @var string
     */
    protected $idKey = 'id';

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
    protected $entityInsert = [];

    /**
     * Danh sách các Entity chờ được update
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityUpdate = [];

    /**
     * Danh sách các Entity chờ được delete
     *
     * @var \Vhmis\Db\MySQL\Entity[]
     */
    protected $entityDelete = [];

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

    /**
     *
     * @var int
     */
    protected $fetchModRow = 0;

    /**
     *
     * @var int
     */
    protected $fetchModSet = 0;

    /**
     * Other ids
     *
     * @var array
     */
    protected $otherIds = [];

    /**
     * Dữ liệu thông tin key khác sau một lần select
     *
     * @var array
     */
    protected $otherIdsData = [];

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
     * @param AdapterInterface $adapter
     *
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

        $this->class = '\\' . get_class($this);

        $class = explode('\\', $this->class);
        $table = $class[count($class) - 1];

        if ($this->table == '') {
            $this->table = Text::camelCaseToUnderscore($table);
        }

        $class[count($class) - 1] = $table . 'Entity';

        $this->entityClass = implode('\\', $class);

        $this->otherIds[] = $this->idKey;

        return $this;
    }

    /**
     * Tìm tất cả dữ liệu
     *
     * - Nếu bảng chưa có giá trị thì sẽ trả về mảng rỗng
     * - Nếu bảng đã có dữ liệu thì sẽ trả về mảng chứa các đối tượng Entity tương ứng với Model
     *
     * @return \Vhmis\Db\MySQL\Entity[]|array[]
     */
    public function findAll()
    {
        $sql = 'select * from `' . $this->table . '`';

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setSql($sql)->execute();

        $data = [];
        $this->otherIdsData = [];

        while ($row = $result->next()) {
            if ($this->fetchModSet === self::FETCH_MOD_SET_ARRAY) {
                $data[] = $this->fetchModRow === self::FETCH_MOD_ROW_ENTITY ? $this->fillRowToEntityClass($row) : $this->fillRowToEntityArray($row);
            } else {
                $data[$row[$this->idKey]] = $this->fetchModRow === self::FETCH_MOD_ROW_ENTITY ? $this->fillRowToEntityClass($row) : $this->fillRowToEntityArray($row);
            }

            foreach ($this->otherIds as $id) {
                $this->otherIdsData[Text::underscoreToCamelCase($id)][] = $row[$id];
            }
        }

        return $data;
    }

    /**
     * Tìm theo primany key
     *
     * @param string $id
     *
     * @return \Vhmis\Db\MySQL\Entity|array|null
     */
    public function findById($id)
    {
        $sql = 'select * from `' . $this->table . '` where `' . $this->idKey . '` = ?';

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setParameters([1 => $id])->setSql($sql)->execute();

        $this->otherIdsData = [];

        if ($row = $result->current()) {
            return $this->fetchModRow === self::FETCH_MOD_ROW_ENTITY ? $this->fillRowToEntityClass($row) : $this->fillRowToEntityArray($row);
        } else {
            return null;
        }
    }

    /**
     * Tìm theo các primany key
     *
     * @param array $ids
     *
     * @return \Vhmis\Db\MySQL\Entity[]|array[]
     */
    public function findByIds($ids)
    {
        if (!is_array($ids) || empty($ids)) {
            return [];
        }

        return $this->find([[$this->idKey, 'in', $ids]]);
    }

    /**
     * Tìm
     *
     * @param array $where Mảng chứa điều kiện tìm kiếm
     * @param array $order Mảng chứa điều kiện sắp xếp
     * @param int   $skip  Số row bỏ qua
     * @param int   $limit Số row lấy
     *
     * @return \Vhmis\Db\MySQL\Entity[]|array[]
     *
     * @throws \Exception
     */
    public function find($where = [], $order = [], $skip = 0, $limit = 0)
    {
        $bindData = [];
        if (is_array($where) && count($where) != 0) {
            $sql = [];
            $pos = 1;

            foreach ($where as $w) {
                $field = $w[0];

                if (isset($w[1]) && isset($w[1])) {
                    $operator = $w[1];
                    $value = $w[2];

                    // Try to camelCaseToUnderscore field name
                    $field = Text::camelCaseToUnderscore($field);

                    // Prepare query
                    $sql_temp = '';
                    if ($operator == 'in') {
                        $sql_temp = '`' . $field . '` in ';
                    } else {
                        $sql[] = '`' . $field . '` ' . $operator . ' ?';
                    }

                    // Bind value
                    if ($operator == 'in') {
                        if (!is_array($value)) {
                            throw new \Exception('Value for IN must be an array');
                        }

                        if (empty($value)) {
                            return [];
                        }

                        $values = [];
                        foreach ($value as $v) {
                            if (is_numeric($v)) {
                                $values[] = $v;
                            } else {
                                $values[] = $this->adapter->qoute($v);
                            }
                        }
                        $sql_temp .= '(' . implode(', ', $values) . ')';
                        $sql[] = $sql_temp;
                    } else {
                        $bindData[$pos] = $value;
                        // Count
                        $pos++;
                    }
                } else {
                    $sql[] = $w[0];
                }
            }

            $sql = 'select * from `' . $this->table . '` where ' . implode(' and ', $sql);
        } else {
            $sql = 'select * from `' . $this->table . '`';
        }

        if (is_array($order)) {
            $orderby = [];

            foreach ($order as $field => $or) {
                $field = Text::camelCaseToUnderscore($field);
                $or = $or === 'asc' ? 'asc' : 'desc';
                $orderby[] = '`' . $field . '` ' . $or;
            }

            if (count($orderby) > 0) {
                $sql .= ' order by ' . implode(', ', $orderby);
            }
        }

        if ($skip != 0 || $limit != 0) {
            $sql .= ' limit ' . $skip . ', ' . $limit;
        }

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setParameters($bindData)->setSql($sql)->execute();

        $data = [];
        $this->otherIdsData = [];

        while ($row = $result->next()) {
            if ($this->fetchModSet === self::FETCH_MOD_SET_ARRAY) {
                $data[] = $this->fetchModRow === self::FETCH_MOD_ROW_ENTITY ? $this->fillRowToEntityClass($row) : $this->fillRowToEntityArray($row);
            } else {
                $data[$row[$this->idKey]] = $this->fetchModRow === self::FETCH_MOD_ROW_ENTITY ? $this->fillRowToEntityClass($row) : $this->fillRowToEntityArray($row);
            }

            foreach ($this->otherIds as $id) {
                if (!isset($this->otherIdsData[Text::underscoreToCamelCase($id)])) {
                    $this->otherIdsData[Text::underscoreToCamelCase($id)] = [];
                }
                $this->otherIdsData[Text::underscoreToCamelCase($id)][] = $row[Text::camelCaseToUnderscore($id)];
            }
        }

        return $data;
    }

    public function findOne($where, $order = [])
    {
        $result = $this->find($where, $order, 0, 1);

        if (count($result) === 0) {
            return null;
        }

        return end($result);
    }

    public function setOtherIds($ids) {
        $this->otherIds = $ids;

        return $this;
    }

    /**
     * Lấy danh sách Ids liên quan
     *
     * @return array
     */
    public function getLastRelatedIds()
    {
        foreach ($this->otherIdsData as $id => $data) {
            $this->otherIdsData[$id] = array_unique($data);
        }

        foreach ($this->otherIds as $id) {
            if (!isset($this->otherIdsData[Text::underscoreToCamelCase($id)])) {
                $this->otherIdsData[Text::underscoreToCamelCase($id)] = [];
            }
        }

        return $this->otherIdsData;
    }

    /**
     * Cập nhật
     */
    public function update($where, $data = null)
    {
        if (is_array($where) && is_array($data) && count($data) > 0) {
            $sqlWhere = [];
            $update = [];
            $bindData = [];
            $pos = 1;

            foreach ($data as $field => $value) {
                $field = Text::camelCaseToUnderscore($field);

                $update[] = '`' . $field . '` = ?';
                $bindData[$pos] = $value;
                $pos++;
            }

            foreach ($where as $w) {
                $field = $w[0];
                $operator = $w[1];
                $value = $w[2];

                // Try to camelCaseToUnderscore field name
                $field = Text::camelCaseToUnderscore($field);

                // Prepare query
                $sql_temp = '';
                if ($operator == 'in') {
                    $sql_temp = '`' . $field . '` in ';
                } else {
                    $sqlWhere[] = '`' . $field . '` ' . $operator . ' ?';
                }

                // Bind value
                if ($operator == 'in') {
                    if (!is_array($value) || empty($value)) {
                        throw new \Exception('Value for IN must be an array');
                    }

                    $values = [];
                    foreach ($value as $v) {
                        if (is_numeric($v)) {
                            $values[] = $v;
                        } else {
                            $values[] = $this->adapter->qoute($v);
                        }
                    }
                    $sql_temp .= '(' . implode(', ', $values) . ')';
                    $sqlWhere[] = $sql_temp;
                } else {
                    $bindData[$pos] = $value;
                    // Count
                    $pos++;
                }
            }

            $sql = 'update `' . $this->table . '` set ';
            $sql .= implode(', ', $update);
            $sql .= ' where ';
            $sql .= implode(' and ', $sqlWhere);
        } else {
            return 0;
        }

        $statement = new Statement;
        $result = $statement->setAdapter($this->adapter)->setParameters($bindData)->setSql($sql)->execute();
        return $result->count();
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần insert vào CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function insertQueue($entity)
    {
        if (!($entity instanceof $this->entityClass)) {
            return $this;
        }

        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityInsert[$id] = $entity;

        return $this;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần update lên CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function updateQueue($entity)
    {
        if (!($entity instanceof $this->entityClass)) {
            return $this;
        }

        $methodGetIdKey = Text::underscoreToCamelCase($this->idKey);
        if ($entity->$methodGetIdKey === null) {
            return $this;
        }

        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        if ($entity->isChanged() === false) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityUpdate[$id] = $entity;

        return $this;
    }

    /**
     * Thêm vào danh sách đợi 1 Entity cần delete khỏi CSDL
     *
     * @param \Vhmis\Db\MySQL\Entity $entity
     *
     * @return \Vhmis\Db\MySQL\Model
     */
    public function deleteQueue($entity)
    {
        if (!($entity instanceof $this->entityClass)) {
            return $this;
        }

        $methodGetIdKey = Text::underscoreToCamelCase($this->idKey);
        if ($entity->$methodGetIdKey === null) {
            return $this;
        }

        $id = spl_object_hash($entity);

        if (isset($this->entityKey[$id])) {
            return $this;
        }

        $this->entityKey[$id] = 1;
        $this->entityDelete[$id] = $entity;

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

        $this->adapter->beginTransaction();

        try {
            $this->doInsert();
            $this->doUpdate();
            $this->doDelete();

            $this->adapter->commit();

            $this->entityHasInserted = [];
            $this->entityHasUpdated = [];
            $this->entityHasDeleted = [];

            return true;
        } catch (\PDOException $e) {
            $this->adapter->rollback();

            $this->rollbackInsert();
            $this->rollbackUpdate();
            $this->rollbackDelete();

            return false;
        }
    }

    public function setFetchMod($rowMod, $setMod)
    {
        $this->fetchModRow = $rowMod;
        $this->fetchModSet = $setMod;

        return $this;
    }

    public function setFetchModEntityWithIdKey()
    {
        return $this->setFetchMod(self::FETCH_MOD_ROW_ENTITY, self::FETCH_MOD_SET_IDARRAY);
    }

    public function setFetchModArrayWithIdKey()
    {
        return $this->setFetchMod(self::FETCH_MOD_ROW_ARRAY, self::FETCH_MOD_SET_IDARRAY);
    }

    public function setDefaultFetchMod()
    {
        $this->fetchModRow = self::FETCH_MOD_ROW_ENTITY;
        $this->fetchModSet = self::FETCH_MOD_SET_ARRAY;

        return $this;
    }

    /**
     * Thực hiện việc insert các entity vào CSDL
     */
    protected function doInsert()
    {
        foreach ($this->entityInsert as $id => $entity) {
            $prepareSQL = $entity->insertSQL();

            $stm = $this->adapter->createStatement('insert into ' . $this->table . ' ' . $prepareSQL['sql'], $prepareSQL['param']);
            $res = $stm->execute();
            if ($res->getLastValue()) {
                $setId = Text::underscoreToCamelCase($this->idKey);
                $entity->$setId = $res->getLastValue();
            }

            $entity->setNewValue();
            $this->entityHasInserted[$id] = $entity;

            unset($this->entityKey[$id]);
            unset($this->entityInsert[$id]);
        }
    }

    /**
     * Thực hiện việc update các entity lên CSDL
     */
    protected function doUpdate()
    {
        foreach ($this->entityUpdate as $id => $entity) {
            $prepareSQL = $entity->updateSQL();
            $getId = Text::underscoreToCamelCase($this->idKey);
            $prepareSQL['param'][':' . $this->idKey] = $entity->$getId;

            $stm = $this->adapter->createStatement('update ' . $this->table . ' set ' . $prepareSQL['sql'] . ' where ' . $this->idKey . ' = :' . $this->idKey, $prepareSQL['param']);
            $res = $stm->execute();

            $entity->setNewValue();

            $this->entityHasUpdated[$id] = $entity;

            unset($this->entityKey[$id]);
            unset($this->entityUpdate[$id]);
        }
    }

    /**
     * Thực hiện việc delete các entity khỏi CSDL
     */
    protected function doDelete()
    {
        foreach ($this->entityDelete as $id => $entity) {
            $getId = Text::underscoreToCamelCase($this->idKey);
            $stm = $this->adapter->createStatement('delete from ' . $this->table . ' where ' . $this->idKey . ' = ?', [1 => $entity->$getId]);
            $res = $stm->execute();
            $entity->setDeleted(true);
            $this->entityHasDeleted[$id] = $entity;
            unset($this->entityKey[$id]);
            unset($this->entityDelete[$id]);
        }
    }

    /**
     * Phục hồi các entity đã được insert lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackInsert()
    {
        foreach ($this->entityHasInserted as $id => $entity) {
            $entity->rollback();

            unset($this->entityHasInserted[$id]);
        }
    }

    /**
     * Phục hồi các entity đã được update lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackUpdate()
    {
        foreach ($this->entityHasUpdated as $id => $entity) {
            $entity->rollback();

            unset($this->entityHasUpdated[$id]);
        }
    }

    /**
     * Phục hồi các entity đã được xóa lại như ban đầu nếu quá trình cập nhật CSDL bị lỗi
     */
    protected function rollbackDelete()
    {
        foreach ($this->entityHasDeleted as $id => $entity) {
            $entity->setDeleted(false);

            unset($this->entityHasDeleted[$id]);
        }
    }

    /**
     * Tạo một đối tượng class Entity từ một kết quả trả về ở cơ sở dữ liệu
     *
     * @param array $row
     *
     * @return
     */
    protected function fillRowToEntityClass($row)
    {
        $entity = new $this->entityClass($row);

        return $entity;
    }

    /**
     * Tạo một mảng Entity từ một kết quả trả về ở cơ sở dữ liệu
     *
     * @param array $row
     *
     * @return array
     */
    protected function fillRowToEntityArray($row)
    {
        $entity = [];

        foreach ($row as $key => $value) {
            $entity[Text::underscoreToCamelCase($key)] = $value;
        }

        return $entity;
    }
}
