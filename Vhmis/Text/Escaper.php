<?php

namespace Vhmis\Text;

use Vhmis\Utils\Text;

/**
 * Context specific methods for use in secure output escaping
 *
 * @link https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet Escape
 */
class Escaper
{
    /**
     * HTML entity map
     *
     * @var array
     */
    protected $entities = array(
        34 => '&quot;', // quotation mark
        38 => '&amp;', // ampersand
        60 => '&lt;', // less-than sign
        62 => '&gt;', // greater-than sign
    );

    /**
     * Escapes HTML Body value.
     *
     * [htmltag]ESCAPED CONTENT[/htmltag]
     *
     * @param string $string
     * @paran string $encoding
     * 
     * @return string
     */
    public function escapeHtml($string, $encoding = 'utf-8')
    {
        $result = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
        
        return $result;
    }

    /**
     * Escapes HTML Attribute value.
     *
     * [htmltag attr="ESCAPED CONTENT" ...]
     *
     * @param string $string
     * @return string
     */
    public function escapeHtmlAttr($string)
    {
        if ($string === '' || ctype_digit($string)) {
            return $string;
        }

        $result = preg_replace_callback('/[^a-z0-9,\.\-_]/iSu', array($this, 'htmlAttrMatcher'), $string);
        return $result;
    }

    /**
     * Escapes JS value.
     *
     * var a = 'ESCAPED CONTENT';
     *
     * @param string $string
     * 
     * @return string
     */
    public function escapeJs($string)
    {
        if ($string === '' || ctype_digit($string)) {
            return $string;
        }

        $result = preg_replace_callback('/[^a-z0-9,\._]/iSu', array($this, 'jsMatcher'), $string);
        return $result;
    }

    /**
     * Escapes URI or Parameter value.
     *
     * @param string $string
     * 
     * @return string
     */
    public function escapeUrl($string)
    {
        return rawurlencode($string);
    }

    /**
     * Escapes CSS value.
     *
     * @param string $string
     * @return string
     */
    public function escapeCss($string)
    {
        if ($string === '' || ctype_digit($string)) {
            return $string;
        }

        $result = preg_replace_callback('/[^a-z0-9]/iSu', array($this, 'cssMatcher'), $string);
        return $result;
    }

    /**
     * Replaces unsafe Html Attribute characters.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function htmlAttrMatcher($matches)
    {
        $chr = $matches[0];
        $ord = ord($chr);

        // Replace undefined characters
        if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r") || ($ord >= 0x7f && $ord <= 0x9f)) {
            return '&#xFFFD;';
        }

        // Replace defined characters
        $ord = $this->getHexOrd($chr);
        if (isset($this->entities[$ord])) {
            return $this->entities[$ord];
        }
        
        if ($ord > 255) {
            return sprintf('&#x%04X;', $ord);
        }
        
        return sprintf('&#x%02X;', $ord);
    }

    /**
     * Replaces unsafe Js characters.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function jsMatcher($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            return sprintf('\\x%02X', ord($chr));
        }
        $chr = Text::convertEncoding($chr, 'UTF-8', 'UTF-16BE');
        
        return sprintf('\\u%04s', strtoupper(bin2hex($chr)));
    }

    /**
     * Replaces unsafe Css characters.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function cssMatcher($matches)
    {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            $ord = ord($chr);
        } else {
            $ord = $this->getHexOrd($chr);
        }
        
        return sprintf('\\%X ', $ord);
    }

    /**
     * Get the UTF-16BE hexadecimal ordinal value for a character.
     *
     * @param string $chr
     *
     * @return string
     */
    protected function getHexOrd($chr)
    {
        if (strlen($chr) > 1) {
            $chr = Text::convertEncoding($chr, 'UTF-8', 'UTF-16BE');
        }

        return hexdec(bin2hex($chr));
    }
}
