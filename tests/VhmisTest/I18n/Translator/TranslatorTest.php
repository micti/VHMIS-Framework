<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Translator;

use Vhmis\I18n\Translator\Loader\PhpArray;
use Vhmis\I18n\Translator\Translator;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{

    public function testTranslate()
    {
        $loader = new PhpArray;
        $loader->setPath(__DIR__ . '/data');

        $translator = new Translator;
        $translator->setLoader($loader);

        $this->assertSame('Xin chào', $translator->translate('hello', null, 'All', 'vi_VN'));
        $this->assertSame('Hello', $translator->translate('hello', null, 'All', 'en_US'));
        $this->assertSame('Chào buổi sáng', $translator->translate('good.morning', null, 'All', 'vi_VN'));

        $data = array(4560, 123, 4560 / 123);
        $this->assertSame(
                '4,560 monkeys on 123 trees make 37.073 monkeys per tree', $translator->translate('monkeys on trees make monkeys per tree', $data, 'Default', 'en_US')
        );
        $this->assertSame(
                '4.560 con khỉ trên 123 cây nên có 37,073 con khi trên mỗi cây', $translator->translate('monkeys on trees make monkeys per tree', $data, 'Default', 'vi_VN')
        );
    }

    public function testFallbackTranslate()
    {
        $loader = new PhpArray;
        $loader->setPath(__DIR__ . '/data');

        $translator = new Translator;
        $translator->setLoader($loader);

        $this->assertSame('good.morning', $translator->translate('good.morning', null, 'All', 'en_US'));

        $translator->setFallbackLocale('vi_VN');
        $this->assertSame('Chào buổi sáng', $translator->translate('good.morning', null, 'All', 'en_US'));
        $this->assertSame('something.not.found.both', $translator->translate('something.not.found.both', null, 'All', 'en_US'));
    }

    public function testTranslateCardinalPlural()
    {
        $loader = new PhpArray;
        $loader->setPath(__DIR__ . '/data');

        $translator = new Translator;
        $translator->setLoader($loader);

        $this->assertSame(
                'Có 1 quả táo trong túi xách.', sprintf($translator->translateCardinalPlural('There are xxx apples in the bag', 1, null, 'Default', 'vi_VN'), 1)
        );

        $this->assertSame(
                'Có 1567 quả táo trong túi xách.', sprintf($translator->translateCardinalPlural('There are xxx apples in the bag', 1567, null, 'Default', 'vi_VN'), 1567)
        );

        $this->assertSame(
                'There is 1 apple in the bag.', sprintf($translator->translateCardinalPlural('There are xxx apples in the bag', 1, null, 'Default', 'en_US'), 1)
        );

        $this->assertSame(
                'There are 467 apples in the bag.', sprintf($translator->translateCardinalPlural('There are xxx apples in the bag', 467, null, 'Default', 'en_US'), 467)
        );
    }
}
