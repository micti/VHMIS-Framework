<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Library\Marc\Format\USMARC\Read;

use Vhmis\Library\Marc\Format\USMARC\Read;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Read object.
     *
     * @var Read
     */
    protected $read;

    public function setUp()
    {
        $this->read = new Read;
    }
    
    public function testNotFile()
    {
        $dir = __DIR__;
        
        $this->assertFalse($this->read->readFile($dir . '/File/Book1222.usm'));
        $this->assertFalse($this->read->readFile($dir . '/File'));
    }
    
    public function testRead()
    {
        $dir = __DIR__;
        $this->read->reset();
        $this->read->readFile($dir . '/File/book1.usm');
        $this->read->readFile($dir . '/File/test2.usm');
        $records = $this->read->getRecords();
        $this->assertEquals(2, count($records));
    }
}