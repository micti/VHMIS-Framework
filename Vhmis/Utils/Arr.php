<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Utils;

/**
 * Array functions
 */
class Arr
{

    /**
     * Flatten array (nested or not).
     *
     * @param array &$array
     * @param array $spec
     * @param string $key For internal using
     * @param string $current For internal using
     */
    static public function flatten(&$array, $spec = '.', $key = '', $current = null)
    {
        if ($current === null) {
            $current = $array;
        }

        foreach ($current as $index => $value) {
            $newkey = $key === '' ? $index : $key . $spec . $index;
            if (is_array($value)) {
                static::flatten($array, $spec, $newkey, $value);
                if ($key === '') {
                    unset($array[$index]);
                }
            } elseif ($key !== '') {
                $array[$newkey] = $value;
            }
        }
    }

    /**
     * Add prefix for array key. (Only keys in first node)
     * 
     * @param array &$array
     * @param string $prefix
     */
    static public function addPrefix(&$array, $prefix)
    {
        $current = $array;

        foreach ($current as $index => $value) {
            $array[$prefix . $index] = $value;
            unset($array[$index]);
        }
    }
}
