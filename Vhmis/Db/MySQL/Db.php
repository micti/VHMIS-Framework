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
