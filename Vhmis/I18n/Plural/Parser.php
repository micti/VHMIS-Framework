<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\Plural;

class Parser
{

    public static function isAccept($number, $rule)
    {
        $rule = trim(static::removeExamples($rule));

        if ($rule === '') {
            return true;
        }

        $operands = static::getOperand($number);
        $orConditions = static::getConditions('or', $rule);

        foreach ($orConditions as $condition) {
            $pass = static::isAcceptedCondition($operands, $condition);
            if ($pass === true) {
                return true;
            }
        }

        return false;
    }

    protected static function isAcceptedCondition($operands, $condition)
    {
        $andConditions = static::getConditions('and', $condition);
        $pass = true;

        foreach ($andConditions as $relation) {
            $pass = $pass && static::isAcceptedRelation($operands, $relation);
        }

        return $pass;
    }

    protected static function isAcceptedRelation($operands, $relation)
    {
        $relationType = static::getRelationType($relation);
        $exps = explode(' ' . $relationType . ' ', $relation);
        foreach ($operands as $key => $value) {
            $exps[0] = str_replace($key, $value, $exps[0]);
        }

        $value = static::calculateSimpleMath($exps[0]);

        $list = trim($exps[1]);

        return static::isAcceptedValue($value, $relationType, $list);
    }

    protected static function isAcceptedValue($value, $relationType, $list)
    {
        $numbers = explode(',', $list);
        $pass = true;

        foreach ($numbers as $number) {
            $range = explode('..', $number, 2);
            $pass = doubleval($value) === doubleval($range[0]);
            if (isset($range[1])) {
                $pass = $value <= (int) $range[1] && $value >= (int) $range[0];
            }
        }

        if ($relationType === '!=') {
            $pass = !$pass;
        }

        return $pass;
    }

    /**
     * Remove examples in rule string.
     * 
     * @param string $rule
     * @return string
     */
    public static function removeExamples($rule)
    {
        $parts = explode('@', $rule, 2);

        return $parts[0];
    }

    /**
     * Get conditions in rule
     * 
     * @param string $type Or or And
     * @param string $rule
     *
     * @return string[]
     */
    public static function getConditions($type, $rule)
    {
        $conditions = explode(' ' . $type . ' ', $rule);

        return $conditions;
    }

    public static function getRelationType($relation)
    {
        $types = ['!=', '='];

        foreach ($types as $type) {
            if (strpos($relation, $type) !== false) {
                return $type;
            }
        }

        return '=';
    }

    public static function getOperand($number)
    {
        if (is_int($number)) {
            $n = (int) $number;
            return [
                'n' => $n,
                'i' => $n,
                'v' => 0,
                'w' => 0,
                'f' => 0,
                't' => 0
            ];
        }

        if (is_double($number)) {
            $end = '';
            if ($number - floor($number) == 0) {
                $end = '.0';
            }

            $number = (string) $number . $end;
        }

        return static::getOperandFromString($number);
    }

    public static function getOperandFromString($string)
    {
        $n = doubleval($string);
        $parts = explode('.', $string);
        $i = (int) $parts[0];

        $f = $v = $w = $t = 0;
        if (isset($parts[1])) {
            $f = intval($parts[1]);
            $v = strlen($parts[1]);
            $w = $n - $i;
            if ($w !== 0.0) {
                $w = strlen((string) $w) - 2;
            } else {
                $w = 0;
            }
            $t = (int) str_replace('0', '', $parts[1]);
        } else {
            $n = (int) $n;
        }

        return [
            'n' => $n,
            'i' => $i,
            'v' => $v,
            'w' => $w,
            'f' => $f,
            't' => $t
        ];
    }
    
    /**
     * Calculate simple math
     * 
     * 4, 4 % 3 ...
     * 
     * @param string $math
     * 
     * @return int
     */    
    protected static function calculateSimpleMath($math)
    {
        $parts = explode(' ', trim($math));
        
        if (count($parts) !== 3) {
            return (int) $parts[0];
        }
        
        if ($parts[1] === '%') {
            return (int) $parts[0] % (int) $parts[2];
        }
        
        return (int) $parts[0];
    }
}
