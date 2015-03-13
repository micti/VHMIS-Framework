<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Utils;

use \Vhmis\Utils\Arr as ArrUtils;

class ArrTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertToOneDimensional()
    {
        $array = [
            'a' => [
                'b' => [
                    'c' => 'd'
                ],
                'c' => 'e'
            ],
            'e' => 'a'
        ];
        
        $result = [
            'a_b_c' => 'd',
            'a_c' => 'e',
            'e' => 'a'
        ];
        
        ArrUtils::flatten($array, '_');
        $this->assertEquals($result, $array);
    }
    
    public function testAddPrefix()
    {
        $array = [
            'a_b_c' => 'd',
            'a_c' => 'e',
            'e' => 'a'
        ];
        
        $result = [
            'hello_a_b_c' => 'd',
            'hello_a_c' => 'e',
            'hello_e' => 'a'
        ];
        
        ArrUtils::addPrefix($array, 'hello_');
        $this->assertEquals($result, $array);
    }
}