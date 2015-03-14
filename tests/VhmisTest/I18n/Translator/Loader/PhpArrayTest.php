<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Translator\Loader;

use Vhmis\I18n\Translator\Loader\PhpArray;

class PhpArrayTest extends \PHPUnit_Framework_TestCase
{

    public function testLoad()
    {
        $result = [
            'hello' => 'Xin chào',
            'good.morning' => 'Chào buổi sáng',
            'good.afternoon' => 'Chào buổi chiều'
        ];

        $path = __DIR__ . '/../data';
        $loader = new PhpArray;
        $loader->setPath($path);
        $this->assertEquals($result, $loader->load('vi_VN', 'All'));
    }
}
