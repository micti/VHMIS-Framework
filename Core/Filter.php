<?php

class Vhmis_Filter
{
    /**
    * Loại bỏ những ký tự không phải là số (0..9)
     *
     * @param mix Giá trị cần lọc
     * @return string Giá trị sau khi lọc
     */
    public function digit($value)
    {
        $pattern = '/[^0-9]/';

        return preg_replace($pattern, '', (string) $value);
    }

    /**
     * Loại bỏ những ký tự không phải là chữ và số
     */
    public function alnum($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $whiteSpace = $allowWhiteSpace ? '\s' : '';

        $pattern = '/[^A-Za-z0-9' . $whiteSpace . ']/';

        if($allowUnicode) $pattern = '/[^\p{L}\p{N}' . $whiteSpace . ']/u';

        return preg_replace($pattern, '', (string) $value);
    }

    /**
     * Loại bỏ những ký tự không phải là chữ
     */
    public function alpha($value, $allowWhiteSpace = false, $allowUnicode = false)
    {
        $whiteSpace = $allowWhiteSpace ? '\s' : '';

        $pattern = '/[^A-Za-z' . $whiteSpace . ']/';

        if($allowUnicode) $pattern = '/[^\p{L}' . $whiteSpace . ']/u';

        return preg_replace($pattern, '', (string) $value);
    }

    /**
     * HTML Entitles
     *
     * @see http://php.net/manual/en/function.htmlentities.php
     */
    public function htmlEntities($value, $quoteStyle = ENT_QUOTES, $encoding = 'utf-8', $doubleQuote = true)
    {
        $filtered = htmlentities((string) $value, $quoteStyle, $encoding, $doubleQuote);
        if (strlen((string) $value) && !strlen($filtered))
        {
            if (!function_exists('iconv'))
            {
                return '';
            }
            $value    = iconv('', $encoding . '//IGNORE', (string) $value);
            $filtered = htmlentities($value, $quoteStyle, $enc, $doubleQuote);
            if (!strlen($filtered))
            {
                return '';
            }
        }
        return $filtered;
    }

    /**
     * Loại bỏ các tag HTML không được phép
     *
     * Based Zend Framework
     *
     * @param  string $value
     * @return string
     */
    public function stripHTML($value, $allowTags = array(), $allowAttributes = array())
    {
        $value = (string) $value;

        if(!is_array($allowTags)) $allowTags = array();
        $_allowTags = array();
        foreach($allowTags as $key => $val)
        {
            if(is_int($key) && is_string($val))
            {
                $_allowTags[strtolower($val)] = array();
            }
            else if(is_string($key) && is_array($val))
            {
                $_allowTags[strtolower($key)] = array();
                foreach($val as $key1 => $val1)
                {
                    if(is_int($key1) && is_string($val1))
                    {
                        $_allowTags[strtolower($key)][strtolower($val1)] = 1;
                    }
                }
            }
        }

        if(!is_array($allowAttributes)) $allowAttributes = array();
        $_allowAttributes = array();
        foreach($allowAttributes as $key => $val)
        {
            if(is_int($key) && is_string($val))
            {
                $_allowAttributes[strtolower($val)] = array();
            }
        }

        // Strip HTML comments first
        while (strpos($value, '<!--') !== false) {
            $pos   = strrpos($value, '<!--');
            $start = substr($value, 0, $pos);
            $value = substr($value, $pos);

            // If there is no comment closing tag, strip whole text
            if (!preg_match('/--\s*>/s', $value)) {
                $value = '';
            } else {
                $value = preg_replace('/<(?:!(?:--[\s\S]*?--\s*)?(>))/s', '',  $value);
            }

            $value = $start . $value;
        }

        // Initialize accumulator for filtered data
        $dataFiltered = '';
        // Parse the input data iteratively as regular pre-tag text followed by a
        // tag; either may be empty strings
        preg_match_all('/([^<]*)(<?[^>]*>?)/', (string) $value, $matches);

        // Iterate over each set of matches
        foreach ($matches[1] as $index => $preTag) {
            // If the pre-tag text is non-empty, strip any ">" characters from it
            if (strlen($preTag)) {
                $preTag = str_replace('>', '', $preTag);
            }
            // If a tag exists in this match, then filter the tag
            $tag = $matches[2][$index];
            if (strlen($tag)) {
                $tagFiltered = $this->_filterTag($tag, $_allowTags, $_allowAttributes);
            } else {
                $tagFiltered = '';
            }
            // Add the filtered pre-tag text and filtered tag to the data buffer
            $dataFiltered .= $preTag . $tagFiltered;
        }

        // Return the filtered data
        return $dataFiltered;
    }

    /**
     * Filters a single tag against the current option settings
     *
     * @param  string $tag
     * @return string
     */
    protected function _filterTag($tag, $allowTags, $allowAttributes)
    {
        // Parse the tag into:
        // 1. a starting delimiter (mandatory)
        // 2. a tag name (if available)
        // 3. a string of attributes (if available)
        // 4. an ending delimiter (if available)
        $isMatch = preg_match('~(</?)(\w*)((/(?!>)|[^/>])*)(/?>)~', $tag, $matches);

        // If the tag does not match, then strip the tag entirely
        if (!$isMatch) {
            return '';
        }

        // Save the matches to more meaningfully named variables
        $tagStart      = $matches[1];
        $tagName       = strtolower($matches[2]);
        $tagAttributes = $matches[3];
        $tagEnd        = $matches[5];

        // If the tag is not an allowed tag, then remove the tag entirely
        if (!isset($allowTags[$tagName])) {
            return '';
        }

        // Trim the attribute string of whitespace at the ends
        $tagAttributes = trim($tagAttributes);

        // If there are non-whitespace characters in the attribute string
        if (strlen($tagAttributes)) {
            // Parse iteratively for well-formed attributes
            preg_match_all('/([\w-]+)\s*=\s*(?:(")(.*?)"|(\')(.*?)\')/s', $tagAttributes, $matches);

            // Initialize valid attribute accumulator
            $tagAttributes = '';

            // Iterate over each matched attribute
            foreach ($matches[1] as $index => $attributeName) {
                $attributeName      = strtolower($attributeName);
                $attributeDelimiter = empty($matches[2][$index]) ? $matches[4][$index] : $matches[2][$index];
                $attributeValue     = empty($matches[3][$index]) ? $matches[5][$index] : $matches[3][$index];

                // If the attribute is not allowed, then remove it entirely
                if (!array_key_exists($attributeName, $allowTags[$tagName])
                    && !array_key_exists($attributeName, $allowAttributes)) {
                    continue;
                }
                // Add the attribute to the accumulator
                $tagAttributes .= " $attributeName=" . $attributeDelimiter
                                . $attributeValue . $attributeDelimiter;
            }
        }

        // Reconstruct tags ending with "/>" as backwards-compatible XHTML tag
        if (strpos($tagEnd, '/') !== false) {
            $tagEnd = " $tagEnd";
        }

        // Return the filtered tag
        return $tagStart . $tagName . $tagAttributes . $tagEnd;
    }
}