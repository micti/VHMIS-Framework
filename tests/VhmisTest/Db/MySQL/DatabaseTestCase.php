<?php

namespace VhmisTest\Db\MySQL;

use Vhmis\Db\MySQL\Adapter;
use Vhmis\Db\MySQL\Db;

abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    
    private $dbAdapter = null;
    private $db = null;
    
    public function setUp()
    {
        $this->setUpTable();
        //$this->getConnection()->createDataSet();
    }

    public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
    
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/data/guestbook.xml');
    }
    
    public function getVhmisDb()
    {
        $config = array(
            'host'       => $GLOBALS['DB_HOST'],
            'type'       => 'MySQL',
            'user'       => $GLOBALS['DB_USER'],
            'pass'       => $GLOBALS['DB_PASSWD'],
            'db'         => $GLOBALS['DB_DBNAME'],
            'persistent' => true,
            'charset'    => 'utf8',
            'auto'       => false,
            'options'    => []
        );

        if ($this->db === null) {
            $this->dbAdapter = new Adapter($config);
            $this->db = new Db($this->dbAdapter);
        }
        
        return $this->db;
    }
    
    /**
     * Set up the test database table
     * 
     * @param   PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection $connection
     */
    protected function setUpTable()
    {
        $pdo = $this->getConnection()->getConnection();

        $sql = "CREATE TABLE IF NOT EXISTS guestbook (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            content VARCHAR(50) NOT NULL,
            user VARCHAR(50) NOT NULL,
            created_date DATETIME NOT NULL
        )";

        $pdo->exec($sql);
        
        $pdo->exec('TRUNCATE TABLE guestbook');
    }
}
