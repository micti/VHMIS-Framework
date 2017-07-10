<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace VhmisTest\Text;

use Vhmis\Text\Escaper;

class EscaperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    public function setUp()
    {
        $this->escaper = new Escaper;
    }

    public function testEscapeUrl()
    {
        $chars = array(
            /* HTML special chars - escape without exception to percent encoding */
            '<'  => '%3C',
            '>'  => '%3E',
            '\'' => '%27',
            '"'  => '%22',
            '&'  => '%26',
            /* Characters beyond ASCII value 255 to hex sequence */
            'Ā'  => '%C4%80',
            /* Punctuation and unreserved check */
            ','  => '%2C',
            '.'  => '.',
            '_'  => '_',
            '-'  => '-',
            ':'  => '%3A',
            ';'  => '%3B',
            '!'  => '%21',
            /* Basic alnums excluded */
            'a'  => 'a',
            'A'  => 'A',
            'z'  => 'z',
            'Z'  => 'Z',
            '0'  => '0',
            '9'  => '9',
            /* Basic control characters and null */
            "\r" => '%0D',
            "\n" => '%0A',
            "\t" => '%09',
            "\0" => '%00',
            /* PHP quirks from the past */
            ' '  => '%20',
            '~'  => '~',
            '+'  => '%2B',
        );

        foreach ($chars as $key => $val) {
            $this->assertEquals($val, $this->escaper->escapeUrl($key));
        }
    }

    public function testEscapeHtml()
    {
        $chars = array(
            "'" => '&#039;',
            '"' => '&quot;',
            '<' => '&lt;',
            '>' => '&gt;',
            '&' => '&amp;'
        );

        foreach ($chars as $key => $val) {
            $this->assertEquals($val, $this->escaper->escapeHtml($key));
        }
    }

    public function testEscapeHtmlAttr()
    {
        $chars = array(
            '\'' => '&#x27;',
            '"'  => '&quot;',
            '<'  => '&lt;',
            '>'  => '&gt;',
            '&'  => '&amp;',
            /* Characters beyond ASCII value 255 to unicode escape */
            'Ā'  => '&#x0100;',
            /* Immune chars excluded */
            ','  => ',',
            '.'  => '.',
            '-'  => '-',
            '_'  => '_',
            /* Basic alnums exluded */
            'a'  => 'a',
            'A'  => 'A',
            'z'  => 'z',
            'Z'  => 'Z',
            '0'  => '0',
            '9'  => '9',
            /* Basic control characters and null */
            "\r" => '&#x0D;',
            "\n" => '&#x0A;',
            "\t" => '&#x09;',
            "\0" => '&#xFFFD;',
            /* Encode chars as named entities where possible */
            '<'  => '&lt;',
            '>'  => '&gt;',
            '&'  => '&amp;',
            '"'  => '&quot;',
            /* Encode spaces for quoteless attribute protection */
            ' '  => '&#x20;',
        );

        foreach ($chars as $key => $val) {
            $this->assertEquals($val, $this->escaper->escapeHtmlAttr($key));
        }
    }

    public function testEscapeJs()
    {
        $chars = array(
            /* HTML special chars - escape without exception to hex */
            '<'  => '\\x3C',
            '>'  => '\\x3E',
            '\'' => '\\x27',
            '"'  => '\\x22',
            '&'  => '\\x26',
            /* Characters beyond ASCII value 255 to unicode escape */
            'Ā'  => '\\u0100',
            /* Immune chars excluded */
            ','  => ',',
            '.'  => '.',
            '_'  => '_',
            /* Basic alnums exluded */
            'a'  => 'a',
            'A'  => 'A',
            'z'  => 'z',
            'Z'  => 'Z',
            '0'  => '0',
            '9'  => '9',
            /* Basic control characters and null */
            "\r" => '\\x0D',
            "\n" => '\\x0A',
            "\t" => '\\x09',
            "\0" => '\\x00',
            /* Encode spaces for quoteless attribute protection */
            ' '  => '\\x20',
        );

        foreach ($chars as $key => $val) {
            $this->assertEquals($val, $this->escaper->escapeJs($key));
        }
    }

    public function testEscapeCss()
    {
        $chars = array(
            /* HTML special chars - escape without exception to hex */
            '<'  => '\\3C ',
            '>'  => '\\3E ',
            '\'' => '\\27 ',
            '"'  => '\\22 ',
            '&'  => '\\26 ',
            /* Characters beyond ASCII value 255 to unicode escape */
            'Ā'  => '\\100 ',
            /* Immune chars excluded */
            ','  => '\\2C ',
            '.'  => '\\2E ',
            '_'  => '\\5F ',
            /* Basic alnums exluded */
            'a'  => 'a',
            'A'  => 'A',
            'z'  => 'z',
            'Z'  => 'Z',
            '0'  => '0',
            '9'  => '9',
            /* Basic control characters and null */
            "\r" => '\\D ',
            "\n" => '\\A ',
            "\t" => '\\9 ',
            "\0" => '\\0 ',
            /* Encode spaces for quoteless attribute protection */
            ' '  => '\\20 ',
        );

        foreach ($chars as $key => $val) {
            $this->assertEquals($val, $this->escaper->escapeCss($key));
        }
    }

    /**
     * Only testing the first few 2 ranges on this prot. function as that's all these
     * other range tests require
    */
    public function testUnicodeCodepointConversionToUtf8()
    {
        $expected = " ~ޙ";
        $codepoints = array(0x20, 0x7e, 0x799);
        $result = '';
        foreach ($codepoints as $value) {
            $result .= $this->codepointToUtf8($value);
        }
        $this->assertEquals($expected, $result);
    }

    /**
     * Convert a Unicode Codepoint to a literal UTF-8 character.
     *
     * @param int Unicode codepoint in hex notation
     * @return string UTF-8 literal string
     */
    protected function codepointToUtf8($codepoint)
    {
        if ($codepoint < 0x80) {
            return chr($codepoint);
        }

        if ($codepoint < 0x800) {
            return chr($codepoint >> 6 & 0x3f | 0xc0) . chr($codepoint & 0x3f | 0x80);
        }

        if ($codepoint < 0x10000) {
            return chr($codepoint >> 12 & 0x0f | 0xe0) . chr($codepoint >> 6 & 0x3f | 0x80)
                . chr($codepoint & 0x3f | 0x80);
        }

        if ($codepoint < 0x110000) {
            return chr($codepoint >> 18 & 0x07 | 0xf0) . chr($codepoint >> 12 & 0x3f | 0x80)
                . chr($codepoint >> 6 & 0x3f | 0x80) . chr($codepoint & 0x3f | 0x80);
        }

        throw new \Exception('Codepoint requested outside of Unicode range');
    }

    public function testJsRanges()
    {
        $immune = array(',', '.', '_'); // Exceptions to escaping ranges
        for ($chr=0; $chr < 0xFF; $chr++) {
            $literal = $this->codepointToUtf8($chr);

            if ($chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $this->assertEquals($literal, $this->escaper->escapeJs($literal));
            } else {
                if (!in_array($literal, $immune)) {
                    $this->assertNotEquals($literal, $this->escaper->escapeJs($literal));
                }
            }
        }
    }

    public function testHtmlAttrRanges()
    {
        $immune = array(',', '.', '-', '_'); // Exceptions to escaping ranges
        for ($chr=0; $chr < 0xFF; $chr++) {
            $literal = $this->codepointToUtf8($chr);

            if ($chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $this->assertEquals($literal, $this->escaper->escapeHtmlAttr($literal));
            } else {
                if (!in_array($literal, $immune)) {
                    $this->assertNotEquals($literal, $this->escaper->escapeHtmlAttr($literal));
                }
            }
        }
    }

    public function testCssRanges()
    {
        for ($chr=0; $chr < 0xFF; $chr++) {
            $literal = $this->codepointToUtf8($chr);

            if ($chr >= 0x30 && $chr <= 0x39
                || $chr >= 0x41 && $chr <= 0x5A
                || $chr >= 0x61 && $chr <= 0x7A
            ) {
                $this->assertEquals($literal, $this->escaper->escapeCss($literal));
            } else {
                $this->assertNotEquals($literal, $this->escaper->escapeCss($literal));
            }
        }
    }
}
