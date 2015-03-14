<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils\Loader;

use Vhmis\Utils\Arr;
use Vhmis\Utils\Exception\InvalidArgumentException;

/**
 * Php array file loader
 */
class PhpArray
{

    /**
     * Load php array from file (config file ...)
     *
     * @param string $file
     * @param string $flatten
     * @param string $flattenSpec
     * 
     * @return array
     */
    static public function load($file, $flatten = false, $flattenSpec = '.')
    {
        if (!is_file($file) || !is_readable($file)) {
            throw new InvalidArgumentException('File not found : ' . $file);
        }

        $data = require $file;

        if (!is_array($data)) {
            throw new InvalidArgumentException('Resource must be an array.');
        }

        if ($flatten === true) {
            return Arr::flatten($data, $flattenSpec);
        }

        return $data;
    }
}
