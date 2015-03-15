<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Resource;

use Vhmis\I18n\Resource\Resource as Res;

class ResourceTest extends \PHPUnit_Framework_TestCase
{

    public function testGetCardinalPluralRule()
    {
        $data = Res::getCardinalPluralRule('bem');
        $result = [
            'pluralRule-count-one' => 'n = 1 @integer 1 @decimal 1.0, 1.00, 1.000, 1.0000',
            'pluralRule-count-other' => ' @integer 0, 2~16, 100, 1000, 10000, 100000, 1000000, … @decimal 0.0~0.9, 1.1~1.6, 10.0, 100.0, 1000.0, 10000.0, 100000.0, 1000000.0, …',
        ];
        $this->assertEquals($result, $data);

        \Locale::setDefault('br_FR');
        $data = Res::getCardinalPluralRule();
        $result = [
            'pluralRule-count-one' => 'n % 10 = 1 and n % 100 != 11,71,91 @integer 1, 21, 31, 41, 51, 61, 81, 101, 1001, … @decimal 1.0, 21.0, 31.0, 41.0, 51.0, 61.0, 81.0, 101.0, 1001.0, …',
            'pluralRule-count-two' => 'n % 10 = 2 and n % 100 != 12,72,92 @integer 2, 22, 32, 42, 52, 62, 82, 102, 1002, … @decimal 2.0, 22.0, 32.0, 42.0, 52.0, 62.0, 82.0, 102.0, 1002.0, …',
            'pluralRule-count-few' => 'n % 10 = 3..4,9 and n % 100 != 10..19,70..79,90..99 @integer 3, 4, 9, 23, 24, 29, 33, 34, 39, 43, 44, 49, 103, 1003, … @decimal 3.0, 4.0, 9.0, 23.0, 24.0, 29.0, 33.0, 34.0, 103.0, 1003.0, …',
            'pluralRule-count-many' => 'n != 0 and n % 1000000 = 0 @integer 1000000, … @decimal 1000000.0, 1000000.00, 1000000.000, …',
            'pluralRule-count-other' => ' @integer 0, 5~8, 10~20, 100, 1000, 10000, 100000, … @decimal 0.0~0.9, 1.1~1.6, 10.0, 100.0, 1000.0, 10000.0, 100000.0, …',
        ];
        $this->assertEquals($result, $data);
    }
}
