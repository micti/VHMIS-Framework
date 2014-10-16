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
     * @param string $string
     * @param string $encoding
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
     * @param string $string
     *
     * @return string
     */
    public function escapeHtmlAttr($string)
    {
        return $this->escape($string, '[^a-z0-9,\.\-_]', 'HtmlAttr');
    }

    /**
     * Escapes JS value.
     *
     * @param string $string
     *
     * @return string
     */
    public function escapeJs($string)
    {
        return $this->escape($string, '[^a-z0-9,\._]', 'Js');
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
     *
     * @return string
     */
    public function escapeCss($string)
    {
        return $this->escape($string, '[^a-z0-9]', 'Css');
    }

    /**
     * Escapes value
     *
     * @param string $string
     * @param string $regex
     * @param string $context
     *
     * @return type
     */
    protected function escape($string, $regex, $context)
    {
        if ($string === '' || ctype_digit($string)) {
            return $string;
        }

        $result = preg_replace_callback('/' . $regex . '/iSu', array($this, 'replace' . $context), $string);

        return $result;
    }

    /**
     * Replaces unsafe Html Attribute characters.
     *
     * @param array $matches
     *
     * @return string
     */
    protected function replaceHtmlAttr($matches)
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
    protected function replaceJs($matches)
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
    protected function replaceCss($matches)
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
