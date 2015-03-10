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
}
