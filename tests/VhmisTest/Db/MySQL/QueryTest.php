<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Db\MySQL;

use Vhmis\Db\MySQL\Query;

/**
 * Query test
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
    protected $query;
    
    public function setUp()
    {
        $this->query = new Query;
    }
    
    public function testCreateInsertStatementQuery()
    {
        $table = 'table1';
        
        $fields = [
            'abc_abc',
            'vfkaka_cjjf',
            'Ahfjf'
        ];
        
        $sql = 'insert into table1 (abc_abc, vfkaka_cjjf, Ahfjf) values (:abc_abc, :vfkaka_cjjf, :Ahfjf)';
        
        $this->assertEquals($sql, $this->query->createInsertStatementQuery($table, $fields));
        
        $params = [
            ':ghgf',
            ':rggfd',
            ':fdsfd'
        ];
        
        $sql = 'insert into table1 (abc_abc, vfkaka_cjjf, Ahfjf) values (:ghgf, :rggfd, :fdsfd)';
        
        $this->assertEquals($sql, $this->query->createInsertStatementQuery($table, $fields, $params));
    }
    
    public function testCreateUpdateStatementQuery()
    {
        $table = 'table1';
        
        $fields = [
            'abc_abc',
            'vfkaka_cjjf',
            'Ahfjf'
        ];
        
        $whereFields = [
            'fd',
            'cd'
        ];
        
        $sql = 'update table1 set abc_abc = :abc_abc, vfkaka_cjjf = :vfkaka_cjjf, Ahfjf = :Ahfjf where fd = :fd and cd = :cd';
        
        $this->assertEquals($sql, $this->query->createUpdateStatementQuery($table, $fields, $whereFields));
        
        $fields = [
            'abc_abc',
            'vfkaka_cjjf',
            'Ahfjf'
        ];
        
        $params = [
            ':abc',
            ':vfkaka',
            ':jf'
        ];
        
        $whereFields = [
            'cd'
        ];
        
        $whereParams = [
            ':fd'
        ];
        
        $sql = 'update table1 set abc_abc = :abc, vfkaka_cjjf = :vfkaka, Ahfjf = :jf where cd = :fd';
        
        $this->assertEquals($sql, $this->query->createUpdateStatementQuery($table, $fields, $whereFields, $params, $whereParams));
    }
}