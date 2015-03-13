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
     * @param string $spec
     */
    static public function flatten($array, $spec = '.')
    {
        static::doFlatten($array, $spec);

        return $array;
    }

    /**
     * Add prefix for array key.
     * 
     * @param array $array
     * @param string $prefix
     */
    static public function addPrefix($array, $prefix)
    {
        foreach ($array as $index => $value) {
            $array[$prefix . $index] = $value;

            if (is_array($value)) {
                $array[$prefix . $index] = static::addPrefix($value, $prefix);
            }

            unset($array[$index]);
        }

        return $array;
    }

    /**
     * Internally flatten array.
     *
     * @param array &$array
     * @param string $spec
     * @param string $key For internal using
     * @param array $current For internal using
     */
    static protected function doFlatten(&$array, $spec, $key = '', $current = null)
    {
        if ($current === null) {
            $current = $array;
        }

        foreach ($current as $index => $value) {
            $newkey = $key === '' ? $index : $key . $spec . $index;
            if (is_array($value)) {
                static::doFlatten($array, $spec, $newkey, $value);
                if ($key === '') {
                    unset($array[$index]);
                }
            } elseif ($key !== '') {
                $array[$newkey] = $value;
            }
        }
    }
}
