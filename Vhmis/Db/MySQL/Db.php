<?php

namespace Vhmis\Db\MySQL;

class Db
{

    /**
     *
     * @var Adapter
     */
    protected $adapter;
    protected $modelNamespace;
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
    /**
     *
     * @var Query
     */
    protected $query;

    public function __construct($adapter)
    {
        if ($adapter instanceof Adapter) {
            $this->adapter = $adapter;
            return true;
        }

        if (is_array($adapter)) {
            $this->adapter = new Adapter($adapter);
        }
    }

    public function setModelNamespace($modelNamespace)
    {
        $this->modelNamespace = $modelNamespace;

        return $this;
    }
    
    /**
     * 
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->em = new EntityManager($this);
        }
        
        return $this->em;
    }
    
    /**
     * 
     * @param Entity $entity
     */
    public function entityForInsert($entity)
    {
        return $this->getEntityManager()->forInsert($entity);
    }
    
    /**
     * 
     * @param Entity $entity
     */
    public function entityForUpdate($entity)
    {
        return $this->getEntityManager()->forUpdate($entity);
    }
    
    /**
     * 
     * @param Entity $entity
     */
    public function entityForDelete($entity)
    {
        return $this->getEntityManager()->forDelete($entity);
    }
    
    /**
     * 
     * @return boolean
     */
    public function save()
    {
        return $this->getEntityManager()->flush();
    }

    /**
     * 
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    /**
     * 
     * @return Query
     */
    public function getQuery()
    {
        if ($this->query === null) {
            $this->query = new Query($this->adapter);
        }
        
        return $this->query;
    }
    
    public function executeUpdate($query, $params = [])
    {
        return $this->adapter->executeUpdate($query, $params);
    }
    
    public function getLastInsertId($name = null)
    {
        return $this->adapter->lastInsertId($name);
    }
}
