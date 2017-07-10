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

class ArrTest extends \PHPUnit\Framework\TestCase
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

        $this->assertEquals($result, ArrUtils::flatten($array, '_'));

        $array = [
            'a' => [
                'b' => [
                    'c' => 'd'
                ],
                'c' => 'e'
            ],
            'c' => [
                'b' => 'f',
                'c' => 'e'
            ]
        ];

        $result = [
            'a_b_c' => 'd',
            'a_c' => 'e',
            'c_b' => 'f',
            'c_c' => 'e'
        ];

        $this->assertEquals($result, ArrUtils::flatten($array, '_'));
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

        $this->assertEquals($result, ArrUtils::addPrefix($array, 'hello_'));

        $array = [
            'a' => [
                'b' => [
                    'c' => 'd'
                ],
                'c' => 'e'
            ],
            'c' => [
                'b' => 'f',
                'c' => 'e'
            ]
        ];

        $result = [
            'hello_a' => [
                'hello_b' => [
                    'hello_c' => 'd'
                ],
                'hello_c' => 'e'
            ],
            'hello_c' => [
                'hello_b' => 'f',
                'hello_c' => 'e'
            ]
        ];

        $this->assertEquals($result, ArrUtils::addPrefix($array, 'hello_'));
    }
}
