<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Resource;

use Vhmis\I18n\Resource\Loader;

class LoaderTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testLoadMainDataWithNotSupportedLocale()
    {
        Loader::loadMainData('listPatterns', 'es-ES');
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testLoadMainDataWithNotSupportedField()
    {
        Loader::loadMainData('ca', 'vi-VN');
    }

    public function testLoadMainData()
    {
        $result = [
            'listPatterns' => [
                'listPattern-type-standard' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0} và {1}',
                    2 => '{0} và {1}',
                ],
                'listPattern-type-unit' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
                'listPattern-type-unit-narrow' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
                'listPattern-type-unit-short' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
            ],
        ];
        $this->assertEquals($result, Loader::loadMainData('listPatterns', 'vi-VN'));
    }

    public function testLoadMainDataWithLang()
    {
        $result = [
            'listPatterns' => [
                'listPattern-type-standard' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0} và {1}',
                    2 => '{0} và {1}',
                ],
                'listPattern-type-unit' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
                'listPattern-type-unit-narrow' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
                'listPattern-type-unit-short' => [
                    'start' => '{0}, {1}',
                    'middle' => '{0}, {1}',
                    'end' => '{0}, {1}',
                    2 => '{0}, {1}',
                ],
            ],
        ];
        $this->assertEquals($result, Loader::loadMainData('listPatterns', 'vi-KR'));
    }

    /**
     * @expectedException \Vhmis\Utils\Exception\InvalidArgumentException
     */
    public function testLoadSupplementalDataWithNotSupportedField()
    {
        Loader::loadSupplementalData('af');
    }

    public function testLoadSupplementalData()
    {
        $data = Loader::loadSupplementalData('plurals');
        $result = 'i = 0 or n = 1 @integer 0, 1 @decimal 0.0~1.0, 0.00~0.04';
        $this->assertEquals($result, $data['am']['pluralRule-count-one']);
    }
}
