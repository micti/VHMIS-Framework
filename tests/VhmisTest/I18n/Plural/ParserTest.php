<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\I18n\Plural;

use Vhmis\I18n\Plural\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    
    public function testRemoveExamples()
    {
        $rule = 'n % 10 = 0 or n % 100 = 11..19 or v = 2 and f % 100 = 11..19 @integer 0, 10~20, 30, 40, 50, 60, 100, 1000, 10000, 100000, 1000000, … @decimal 0.0, 10.0, 11.0, 12.0, 13.0, 14.0, 15.0, 16.0, 100.0, 1000.0, 10000.0, 100000.0, 1000000.0, …';
        $result = 'n % 10 = 0 or n % 100 = 11..19 or v = 2 and f % 100 = 11..19 ';
        $this->assertSame($result, Parser::removeExamples($rule));
        
        $rule = 'i = 1 and v = 0 @integer 1';
        $result = 'i = 1 and v = 0 ';
        $this->assertSame($result, Parser::removeExamples($rule));
        
        $rule = 'i = 1';
        $result = 'i = 1';
        $this->assertSame($result, Parser::removeExamples($rule));
    }
    
    public function testGetConditions()
    {
        $rule = 'n % 10 = 0 or n % 100 = 11..19 or v = 2 and f % 100 = 11..19 ';
        $result = [
            'n % 10 = 0',
            'n % 100 = 11..19',
            'v = 2 and f % 100 = 11..19 '
        ];
        $this->assertSame($result, Parser::getConditions('or', $rule));
        
        $rule = 'v = 2 and f % 100 = 11..19 ';
        $result = [
            'v = 2',
            'f % 100 = 11..19 '
        ];
        $this->assertSame($result, Parser::getConditions('and', $rule));
    }
    
    public function testGetRelationType()
    {
        $rule = 'n % 10 = 0 ';
        $this->assertSame('=', Parser::getRelationType($rule));
        
        $rule = 'n % 10 != 0 ';
        $this->assertSame('!=', Parser::getRelationType($rule));
    }
    
    public function testGetOperand()
    {
        $n = 1;
        $result = [
            'n' => 1,
            'i' => 1,
            'v' => 0,
            'w' => 0,
            'f' => 0,
            't' => 0
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = '2';
        $result = [
            'n' => 2,
            'i' => 2,
            'v' => 0,
            'w' => 0,
            'f' => 0,
            't' => 0
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = '2';
        $result = [
            'n' => 2,
            'i' => 2,
            'v' => 0,
            'w' => 0,
            'f' => 0,
            't' => 0
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = '1.00';
        $result = [
            'n' => 1.0,
            'i' => 1,
            'v' => 2,
            'w' => 0,
            'f' => 0,
            't' => 0
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = 1.0;
        $result = [
            'n' => 1.0,
            'i' => 1,
            'v' => 1,
            'w' => 0,
            'f' => 0,
            't' => 0
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = 1.3;
        $result = [
            'n' => 1.3,
            'i' => 1,
            'v' => 1,
            'w' => 1,
            'f' => 3,
            't' => 3
        ];
        $this->assertSame($result, Parser::getOperand($n));
        
        $n = '1.30';
        $result = [
            'n' => 1.3,
            'i' => 1,
            'v' => 2,
            'w' => 1,
            'f' => 30,
            't' => 3
        ];
        
        $n = '1.03';
        $result = [
            'n' => 1.03,
            'i' => 1,
            'v' => 2,
            'w' => 2,
            'f' => 3,
            't' => 3
        ];
        $this->assertSame($result, Parser::getOperand($n));
    }
    
    public function testParser()
    {
        $rule = ' @integer 1, 11, 21, 31, 41, 51, 61, 71, 101, 1001, … @decimal 0.0~1.5, 10.0, 100.0, 1000.0, 10000.0, 100000.0, 1000000.0, …';
        $this->assertTrue(Parser::isAccept(1, $rule));
        $this->assertTrue(Parser::isAccept(1.0, $rule));
        $this->assertTrue(Parser::isAccept('2', $rule));
        $this->assertTrue(Parser::isAccept('11', $rule));
        $this->assertTrue(Parser::isAccept('15.7', $rule));
        $this->assertTrue(Parser::isAccept(29, $rule));
        
        $rule = 'v = 0 and i % 10 = 1 @integer 1, 11, 21, 31, 41, 51, 61, 71, 101, 1001, …';
        $this->assertTrue(Parser::isAccept(1, $rule));
        $this->assertFalse(Parser::isAccept(1.0, $rule));
        $this->assertFalse(Parser::isAccept('2', $rule));
        $this->assertTrue(Parser::isAccept('11', $rule));
        $this->assertFalse(Parser::isAccept('15.7', $rule));
        $this->assertFalse(Parser::isAccept(29, $rule));
        
        $rule = 'v != 0   @decimal 0.0~1.5, 10.0, 100.0, 1000.0, 10000.0, 100000.0, 1000000.0, …';
        $this->assertTrue(Parser::isAccept(1.0, $rule));
        $this->assertTrue(Parser::isAccept(0.135, $rule));
        $this->assertFalse(Parser::isAccept('2', $rule));
        $this->assertTrue(Parser::isAccept('11.0', $rule));
        $this->assertTrue(Parser::isAccept('15.7', $rule));
        $this->assertFalse(Parser::isAccept(29, $rule));
        
        $rule = 'v = 0 and n != 0..10 and n % 10 = 0 @integer 20, 30, 40, 50, 60, 70, 80, 90, 100, 1000, 10000, 100000, 1000000, …';
        $this->assertFalse(Parser::isAccept(10, $rule));
        $this->assertFalse(Parser::isAccept('0', $rule));
        $this->assertTrue(Parser::isAccept('20', $rule));
        $this->assertFalse(Parser::isAccept('15.7', $rule));
        $this->assertFalse(Parser::isAccept(29, $rule));
    }
}
