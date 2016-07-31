<?php

namespace Vhmis\Db\MySQL;

class Query
{
    public function __construct($adapter = null)
    {
        $this->adapter = $adapter;
    }
    
    /**
     * Create statement query for insert
     * 
     * @param string $table
     * @param array $fields
     * @param array $params
     * 
     * @return string
     */
    public function createInsertStatementQuery($table, $fields, $params = null)
    {
        if ($params === null) {
            $params = [];
            
            foreach ($fields as $field) {
                $params[] = ':' . $field;
            }
        }
        
        $query = 'insert into ' . $table . ' (' . implode(', ', $fields) . ') values (' . implode(', ', $params) . ')';
        
        return $query;
    }
    
    public function createUpdateStatementQuery($table, $fields, $whereFields, $params = null, $whereParams = null)
    {
        $setPart = [];
        foreach ($fields as $key => $field) {
            $setPart[] = $field . ' = ' . ($params === null ? ':' . $field : $params[$key]);
        }
        
        $wherePart = [];
        foreach ($whereFields as $key => $field) {
            $wherePart[] = $field . ' = ' . ($whereParams === null ? ':' . $field : $whereParams[$key]);
        }
        
        $query = 'update ' . $table . ' set ' . implode(', ', $setPart) . ' where ' . implode(' and ', $wherePart) . '';
        
        return $query;
    }
}