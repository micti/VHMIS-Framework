<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Library\Marc\Format\USMARC;

use Vhmis\Library\Marc\File\USMARC\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Read object.
     *
     * @var Reader
     */
    protected $reader;

    public function setUp()
    {
        $this->reader = new Reader;
    }
    
    public function testNotFile()
    {
        $dir = __DIR__;
        
        $this->assertFalse($this->reader->readFile($dir . '/File/Book1222.usm'));
        $this->assertFalse($this->reader->readFile($dir . '/File'));
    }
    
    public function testRead()
    {
        $dir = __DIR__;
        $this->reader->reset();
        $this->reader->readFile($dir . '/File/book1.usm');
        $this->reader->readFile($dir . '/File/test2.usm');
        $records = $this->reader->getRecords();
        $this->assertEquals(2, count($records));
    }
}