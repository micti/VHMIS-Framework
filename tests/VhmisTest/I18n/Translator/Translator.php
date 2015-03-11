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

        $this->assertSame('Xin chào', $translator->translate('hello', 'All', 'vi_VN'));
    }

    public function testTranslatePlural()
    {
        $loader = new PhpArray;
        $loader->setPath(__DIR__ . '/data');

        $translator = new Translator;
        $translator->setLoader($loader);

        $this->assertSame(
                'Có 1 quả táo trong túi xách.', sprintf($translator->translatePlural('There are xxx apples in the bag.', 1, 'Default', 'vi_VN'), 1)
        );

        $this->assertSame(
                'Có 1567 quả táo trong túi xách.', sprintf($translator->translatePlural('There are xxx apples in the bag.', 1567, 'Default', 'vi_VN'), 1567)
        );

        $this->assertSame(
                'There is 1 apple in the bag.', sprintf($translator->translatePlural('There are xxx apples in the bag.', 1, 'Default', 'en_US'), 1)
        );

        $this->assertSame(
                'There are 467 apples in the bag.', sprintf($translator->translatePlural('There are xxx apples in the bag.', 467, 'Default', 'en_US'), 467)
        );
    }
}
