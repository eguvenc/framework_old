<?php

/**
 * A port of [phputf8](http://phputf8.sourceforge.net/) to a unified set of files. Provides multi-byte aware replacement string functions.
 *
 * For UTF-8 support to work correctly, the following requirements must be met:
 *
 * - PCRE needs to be compiled with UTF-8 support (--enable-utf8)
 * - Support for [Unicode properties](http://php.net/manual/reference.pcre.pattern.modifiers.php)
 *   is highly recommended (--enable-unicode-properties)
 * - UTF-8 conversion will be much more reliable if the
 *   [iconv extension](http://php.net/iconv) is loaded
 * - The [mbstring extension](http://php.net/mbstring) is highly recommended,
 *   but must not be overloading string functions
 *
 * [!!] This file is licensed differently from the rest of Obullo. As a port of
 * [phputf8](http://phputf8.sourceforge.net/), this file is released under the LGPL.
 *
 * Originally borrowed from Kohana Framework, Harry Fuecks and Andreas Gohr.
 * 
 * @package    Obullo
 * @author     Obullo Team
 * @license    Utf8 library licenced under the http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */

Class Utf8 {
    
    /**
    * Constructor - Set server utf8 extension.
    * Determine if this server supports UTF-8 natively
    *
    * @return void
    */
    public function __construct($no_instance = true)
    {
        if( ! extension_loaded('mbstring'))
        {
            throw new Exception('Mbstring extension not loaded.');
        }
        
        if($no_instance)
        {
            getInstance()->utf8 = $this; // Available it in the contoller $this->utf8->method();
        }
        
        log\me('debug', "Utf8 Class Initialized");
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Initialize to Class.
     * 
     * @return object
     */
    public function init()
    {
        return ($this);
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Recursively cleans arrays, objects, and strings. Removes ASCII control
    * codes and converts to the requested charset while silently discarding
    * incompatible characters.
    *
    *     $this->utf8->clean($_GET); // Clean GET data
    *
    * [!!] This method requires [Iconv](http://php.net/iconv)
    *
    * @param   mixed   variable to clean
    * @param   string  character set, defaults to config('charset')
    * @return  mixed
    * @uses    $this->utf8->stripAsciiCtrl
    * @uses    $this->utf8->is_ascii
    */
    public function clean($var, $charset = null)
    {
        if ( ! $charset)
        {
            $charset = config('charset');    // Use the application character set
        }

        if (is_array($var) OR is_object($var))
        {
            foreach ($var as $key => $val)
            {
                $var[$this->clean($key)] = $this->clean($val);
            }
        }
        elseif (is_string($var) AND $var !== '')
        {
            $var = $this->stripAsciiCtrl($var);  // Remove control characters

            if ( ! $this->isAscii($var))
            {
                $error_reporting = error_reporting(~E_NOTICE); // Disable notices
                
                $var = iconv($charset, $charset.'//IGNORE', $var); // iconv is expensive, so it is only used when needed

                error_reporting($error_reporting);  // Turn notices back on
            }
        }

        return $var;
    }
    
    // ------------------------------------------------------------------------

    /**
    * Tests whether a string contains only 7-bit ASCII bytes. This is used to
    * determine when to use native functions or UTF-8 functions.
    *
    *     $ascii = $this->utf8->isAscii($str);
    *
    * @param   mixed    string or array of strings to check
    * @return  boolean
    */
    public function isAscii($str)
    {
        if (is_array($str))
        {
            $str = implode($str);
        }

        return ! preg_match('/[^\x00-\x7F]/S', $str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Strips out device control codes in the ASCII range.
    *
    *     $str = $this->utf8->stripAsciiCtrl($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    public function stripAsciiCtrl($str)
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Strips out all non-7bit ASCII bytes.
    *
    *     $str = $this->utf8->stripNonAscii($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    public function stripNonAscii($str)
    {
        return preg_replace('/[^\x00-\x7F]+/S', '', $str);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Replaces special/accented UTF-8 characters by ASCII-7 "equivalents".
     *
     *     $ascii = $this->utf8->toAscii($utf8);
     *
     * @param   string   string to transliterate
     * @param   integer  -1 lowercase only, +1 uppercase only, 0 both cases
     * @return  string
     */
    public function toAscii($str, $case = 0)
    {
        $object = new Utf8\Src\To_Ascii();
        return $object->start($str, $case);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns the length of the given string. This is a UTF8-aware version
    * of [strlen](http://php.net/strlen).
    *
    *     $length = $this->utf8->strlen($str);
    *
    * @param   string   string being measured for length
    * @return  integer
    */
    public function strlen($str)
    {
        return mb_strlen($str, config('charset'));
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds position of first occurrence of a UTF-8 string. This is a
    * UTF8-aware version of [strpos](http://php.net/strpos).
    *
    *     $position = $this->utf8->strpos($str, $search);
    *
    * @param   string   haystack
    * @param   string   needle
    * @param   integer  offset from which character in haystack to start searching
    * @return  integer  position of needle
    * @return  boolean  false if the needle is not found
    */
    public function strpos($str, $search, $offset = 0)
    {
        return mb_strpos($str, $search, $offset, config('charset'));
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds position of last occurrence of a char in a UTF-8 string. This is
    * a UTF8-aware version of [strrpos](http://php.net/strrpos).
    *
    *     $position = $this->utf8->strrpos($str, $search);
    *
    * @param   string   haystack
    * @param   string   needle
    * @param   integer  offset from which character in haystack to start searching
    * @return  integer  position of needle
    * @return  boolean  false if the needle is not found
    */
    public function strrpos($str, $search, $offset = 0)
    {
        return mb_strrpos($str, $search, $offset, config('charset'));
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns part of a UTF-8 string. This is a UTF8-aware version
    * of [substr](http://php.net/substr).
    *
    *     $sub = $this->utf8->substr($str, $offset);
    *
    * @param   string   input string
    * @param   integer  offset
    * @param   integer  length limit
    * @return  string
    * @uses    config('charset');
    */
    public function substr($str, $offset, $length = null)
    {
        return ($length === null) 
            ? mb_substr($str, $offset, mb_strlen($str), config('charset')) 
                    : mb_substr($str, $offset, $length, config('charset'));

    }

    // ------------------------------------------------------------------------
    
    /**
    * Replaces text within a portion of a UTF-8 string. This is a UTF8-aware
    * version of [substr_replace](http://php.net/substr_replace).
    *
    *     $str = $this->utf8->substrReplace($str, $replacement, $offset);
    *
    * @param   string   input string
    * @param   string   replacement string
    * @param   integer  offset
    * @return  string
    */
    public function substrReplace($str, $replacement, $offset, $length = null)
    {
        $object = new Utf8\Src\Substr_Replace();
        return $object->start($str, $replacement, $offset, $length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes a UTF-8 string lowercase. This is a UTF8-aware version
    * of [strtolower](http://php.net/strtolower).
    *
    *     $str = $this->utf8->strtolower($str);
    *
    * @param   string   mixed case string
    * @return  string
    */
    public function strtolower($str)
    {
        if(strpos($str, 'İ') !== false)  // İ - i problem in just one Turkish Character.
        {
            $str = str_replace('İ', 'i', $str);
        }
        
        return mb_strtolower($str, config('charset')); 
    }
    
    // ------------------------------------------------------------------------

    /**
    * Makes a UTF-8 string uppercase. This is a UTF8-aware version
    * of [strtoupper](http://php.net/strtoupper).
    *
    * @author  Andreas Gohr <andi@splitbrain.org>
    * @param   string   mixed case string
    * @return  string
    */
    public function strtoupper($str)
    {
        return mb_strtoupper($str, config('charset'));
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes a UTF-8 string's first character uppercase. This is a UTF8-aware
    * version of [ucfirst](http://php.net/ucfirst).
    *
    *     $str = $this->utf8->ucfirst($str);
    *
    * @author  Harry Fuecks <hfuecks@gmail.com>
    * @param   string   mixed case string
    * @return  string
    */
    public function ucfirst($str)
    {
        if(strpos($str, 'i') === 0)  // i - I problem in Turkish Characters .
        {
            $str = 'İ'. $this->substr($str, 1);
        }
        
        $object = new Utf8\Src\Ucfirst();
        return $object->start($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes the first character of every word in a UTF-8 string uppercase.
    * This is a UTF8-aware version of [ucwords](http://php.net/ucwords).
    *
    *     $str = $this->utf8->ucwords($str);
    *
    * @param   string   mixed case string
    * @return  string
    */
    public function ucwords($str)
    {
        if(strpos($str, 'i') === 0)  // i - I problem in Turkish Characters .
        {
            $str = 'İ'. $this->substr($str, 1);
        }
        
        $object = new Utf8\Src\Ucwords();
        return $object->start($str);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Case-insensitive UTF-8 string comparison. This is a UTF8-aware version
    * of [strcasecmp](http://php.net/strcasecmp).
    *
    *     $compare = $this->utf8->strcasecmp($str1, $str2);
    *
    * @param   string   string to compare
    * @param   string   string to compare
    * @return  integer  less than 0 if str1 is less than str2
    * @return  integer  greater than 0 if str1 is greater than str2
    * @return  integer  0 if they are equal
    */
    public function strcasecmp($str1, $str2)
    {
        $object = new Utf8\Src\Strcasecmp();
        return $object->start($str1, $str2);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns a string or an array with all occurrences of search in subject
    * (ignoring case) and replaced with the given replace value. This is a
    * UTF8-aware version of [str_ireplace](http://php.net/str_ireplace).
    *
    * [!!] This function is very slow compared to the native version. Avoid
    * using it when possible.
    *
    * @param   string|array  text to replace
    * @param   string|array  replacement text
    * @param   string|array  subject text
    * @param   integer       number of matched and replaced needles will be returned via this parameter which is passed by reference
    * @return  string        if the input was a string
    * @return  array         if the input was an array
    */
    public function strIreplace($search, $replace, $str, & $count = null)
    {
        $object = new Utf8\Src\Str_Ireplace();
        return $object->start($search, $replace, $str, $count);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Case-insenstive UTF-8 version of strstr. Returns all of input string
    * from the first occurrence of needle to the end. This is a UTF8-aware
    * version of [stristr](http://php.net/stristr).
    *
    *     $found = $this->utf8->stristr($str, $search);
    *
    * @param   string  input string
    * @param   string  needle
    * @return  string  matched substring if found
    * @return  false   if the substring was not found
    */
    public function stristr($str, $search)
    {
        $object = new Utf8\Src\Stristr();
        return $object->start($str, $search);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds the length of the initial segment matching mask. This is a
    * UTF8-aware version of [strspn](http://php.net/strspn).
    *
    *     $found = $this->utf8->strspn($str, $mask);
    *
    * @param   string   input string
    * @param   string   mask for search
    * @param   integer  start position of the string to examine
    * @param   integer  length of the string to examine
    * @return  integer  length of the initial segment that contains characters in the mask
    */
    public function strspn($str, $mask, $offset = null, $length = null)
    {
        $object = new Utf8\Src\Strspn();
        return $object->start($str, $mask, $offset, $length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds the length of the initial segment not matching mask. This is a
    * UTF8-aware version of [strcspn](http://php.net/strcspn).
    *
    *     $found = $this->utf8->strcspn($str, $mask);
    *
    * @param   string   input string
    * @param   string   mask for search
    * @param   integer  start position of the string to examine
    * @param   integer  length of the string to examine
    * @return  integer  length of the initial segment that contains characters not in the mask
    */
    public function strcspn($str, $mask, $offset = null, $length = null)
    {
        $object = new Utf8\Src\Strcspn();
        return $object->start($str, $mask, $offset, $length);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Pads a UTF-8 string to a certain length with another string. This is a
    * UTF8-aware version of [str_pad](http://php.net/str_pad).
    *
    *     $str = $this->utf8->str_pad($str, $length);
    *
    * @param   string   input string
    * @param   integer  desired string length after padding
    * @param   string   string to use as padding
    * @param   string   padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
    * @return  string
    */
    public function strPad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $object = new Utf8\Src\Str_Pad();
        return $object->start($str, $final_str_length, $pad_str, $pad_type);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Converts a UTF-8 string to an array. This is a UTF8-aware version of
    * [str_split](http://php.net/str_split).
    *
    *     $array = $this->utf8->str_split($str);
    *
    * @param   string   input string
    * @param   integer  maximum length of each chunk
    * @return  array
    */
    public function strSplit($str, $split_length = 1)
    {
        $object = new Utf8\Src\Str_Split();
        return $object->start($str, $split_length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
    *
    *     $str = $this->utf8->strrev($str);
    *
    * @param   string   string to be reversed
    * @return  string
    */
    public function strrev($str)
    {
        $object = new Utf8\Src\Strrev();
        return $object->start($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning and
    * end of a string. This is a UTF8-aware version of [trim](http://php.net/trim).
    *
    *     $str = $this->utf8->trim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function trim($str, $charlist = null)
    {
        $object = new Utf8\Src\Trim();
        return $object->start($str, $charlist);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning of
    * a string. This is a UTF8-aware version of [ltrim](http://php.net/ltrim).
    *
    *     $str = $this->utf8->ltrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function ltrim($str, $charlist = null)
    {
        $object = new Utf8\Src\Ltrim();
        return $object->start($str, $charlist);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the end of a string.
    * This is a UTF8-aware version of [rtrim](http://php.net/rtrim).
    *
    *     $str = $this->utf8->rtrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function rtrim($str, $charlist = null)
    {
        $object = new Utf8\Src\Rtrim();
        return $object->start($str, $charlist);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns the unicode ordinal for a character. This is a UTF8-aware
    * version of [ord](http://php.net/ord).
    *
    *     $digit = $this->utf8->ord($character);
    *
    * @param   string   UTF-8 encoded character
    * @return  integer
    */
    public function ord($chr)
    {
        $object = new Utf8\Src\Ord();
        return $object->start($chr);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
    * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
    * Occurrences of the BOM are ignored. Surrogates are not allowed.
    *
    *     $array = $this->utf8->toUnicode($str);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
    *
    * @param   string  UTF-8 encoded string
    * @return  array   unicode code points
    * @return  false   if the string is invalid
    */
    public function toUnicode($str)
    {
        $object = new Utf8\Src\To_Unicode();
        return $object->start($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
    * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
    * Occurrances of the BOM are ignored. Surrogates are not allowed.
    *
    *     $str = $this->utf8->fromUnicode($array);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
    *
    * @param   array    unicode code points representing a string
    * @return  string   utf8 string of characters
    * @return  boolean  false if a code point cannot be found
    */
    public function fromUnicode($arr = array())
    {
        $object = new Utf8\Src\From_Unicode();
        return $object->start($arr);
    }

}

/* End of file utf8.php */
/* Location: ./ob/utf8/releases/0.0.1/utf8.php */
