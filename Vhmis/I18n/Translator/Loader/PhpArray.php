<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Translator\Loader;

use Vhmis\Utils\Loader\PhpArray as Loader;

class PhpArray implements FileLoaderInterface
{

    protected $path = '';

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function load($locale, $domain)
    {
        $path = $this->path . D_SPEC . $locale . D_SPEC . $domain . '.php';

        return Loader::load($path, true);
    }
}
