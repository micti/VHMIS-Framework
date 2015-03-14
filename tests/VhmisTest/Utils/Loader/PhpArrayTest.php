<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Utils\Loader;

use Vhmis\Utils\Loader\PhpArray as Loader;

class PhpArrayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testNotFound()
    {
        $path = __DIR__ . '/Data/NotFound';
        $loader = new Loader;
        $loader->load($path);
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testNotValidArray()
    {
        $path = __DIR__ . '/Data/NotValid.php';
        $loader = new Loader;
        $loader->load($path);
    }

    public function testLoad()
    {
        $path = __DIR__ . '/Data/Data.php';
        $loader = new Loader;
        $result = [
            'a' => 'c',
            'b' => 'd',
            'e' => [
                'e' => '1'
            ]
        ];
        $this->assertEquals($result, $loader->load($path));
    }
    
    public function testLoadWithFlatten()
    {
        $path = __DIR__ . '/Data/Data.php';
        $loader = new Loader;
        $result = [
            'a' => 'c',
            'b' => 'd',
            'e_e' => '1'
        ];
        $this->assertEquals($result, $loader->load($path, true, '_'));
    }
}
