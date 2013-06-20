<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo    
 * @subpackage      Obullo.core    
 * @author          obullo.com
 * @copyright       Obullo Team
 * @since           Version 1.0.1
 * @filesource
 * @license
 */

/**
 * A port of [phputf8](http://phputf8.sourceforge.net/) to a unified set
 * of files. Provides multi-byte aware replacement string functions.
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
 * Some codes borrowed from Kohana Framework, Harry Fuecks and Andreas Gohr.
 * 
 * @package    Obullo
 * @license    Utf8 library licenced under the http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */

Class OB_Utf8 {

    public static $server_utf8 = NULL;      // Does the server support UTF-8 natively?
    public static $called      = array();   // List of called methods that have had their required file included.
    
    /**
    * Constructor - Set server utf8 extension.
    * Determine if this server supports UTF-8 natively
    *
    * @return void
    */
    public function __construct()
    {
        if (self::$server_utf8 === NULL)
        {
            self::$server_utf8 = extension_loaded('mbstring');  // Determine if this server supports UTF-8 natively
        }
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Recursively cleans arrays, objects, and strings. Removes ASCII control
    * codes and converts to the requested charset while silently discarding
    * incompatible characters.
    *
    *     OB_Utf8->clean($_GET); // Clean GET data
    *
    * [!!] This method requires [Iconv](http://php.net/iconv)
    *
    * @param   mixed   variable to clean
    * @param   string  character set, defaults to Kohana::$charset
    * @return  mixed
    * @uses    OB_Utf8->strip_ascii_ctrl
    * @uses    OB_Utf8->is_ascii
    */
    public function clean($var, $charset = NULL)
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
            $var = $this->strip_ascii_ctrl($var);  // Remove control characters

            if ( ! $this->is_ascii($var))
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
    *     $ascii = OB_Utf8->is_ascii($str);
    *
    * @param   mixed    string or array of strings to check
    * @return  boolean
    */
    public function is_ascii($str)
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
    *     $str = OB_Utf8->strip_ascii_ctrl($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    public function strip_ascii_ctrl($str)
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Strips out all non-7bit ASCII bytes.
    *
    *     $str = OB_Utf8->strip_non_ascii($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    public function strip_non_ascii($str)
    {
        return preg_replace('/[^\x00-\x7F]+/S', '', $str);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Replaces special/accented UTF-8 characters by ASCII-7 "equivalents".
     *
     *     $ascii = OB_Utf8->transliterate_to_ascii($utf8);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string   string to transliterate
     * @param   integer  -1 lowercase only, +1 uppercase only, 0 both cases
     * @return  string
     */
    public function transliterate_to_ascii($str, $case = 0)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT;

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_transliterate_to_ascii($str, $case);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns the length of the given string. This is a UTF8-aware version
    * of [strlen](http://php.net/strlen).
    *
    *     $length = OB_Utf8->strlen($str);
    *
    * @param   string   string being measured for length
    * @return  integer
    * @uses    OB_Utf8::$server_utf8
    */
    public function strlen($str)
    {
        if (self::$server_utf8)
        {
            return mb_strlen($str, config('charset'));
        }
            
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_strlen($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds position of first occurrence of a UTF-8 string. This is a
    * UTF8-aware version of [strpos](http://php.net/strpos).
    *
    *     $position = OB_Utf8->strpos($str, $search);
    *
    * @author  Harry Fuecks <hfuecks@gmail.com>
    * @param   string   haystack
    * @param   string   needle
    * @param   integer  offset from which character in haystack to start searching
    * @return  integer  position of needle
    * @return  boolean  FALSE if the needle is not found
    * @uses    UTF8::$server_utf8
    */
    public function strpos($str, $search, $offset = 0)
    {
        if (self::$server_utf8)
        {
            return mb_strpos($str, $search, $offset, config('charset'));
        }

        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_strpos($str, $search, $offset);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds position of last occurrence of a char in a UTF-8 string. This is
    * a UTF8-aware version of [strrpos](http://php.net/strrpos).
    *
    *     $position = OB_Utf8->strrpos($str, $search);
    *
    * @author  Harry Fuecks <hfuecks@gmail.com>
    * @param   string   haystack
    * @param   string   needle
    * @param   integer  offset from which character in haystack to start searching
    * @return  integer  position of needle
    * @return  boolean  FALSE if the needle is not found
    * @uses    OB_Utf8::$server_utf8
    */
    public function strrpos($str, $search, $offset = 0)
    {
        if (self::$server_utf8)
        {
            return mb_strrpos($str, $search, $offset, config('charset'));
        }
            
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_strrpos($str, $search, $offset);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns part of a UTF-8 string. This is a UTF8-aware version
    * of [substr](http://php.net/substr).
    *
    *     $sub = OB_Utf8->substr($str, $offset);
    *
    * @author  Chris Smith <chris@jalakai.co.uk>
    * @param   string   input string
    * @param   integer  offset
    * @param   integer  length limit
    * @return  string
    * @uses    UTF8::$server_utf8
    * @uses    config('charset');
    */
    public function substr($str, $offset, $length = NULL)
    {
        if (self::$server_utf8)
        {
            return ($length === NULL) 
            ? mb_substr($str, $offset, mb_strlen($str), config('charset')) 
                    : mb_substr($str, $offset, $length, config('charset'));
        }
            
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_substr($str, $offset, $length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Replaces text within a portion of a UTF-8 string. This is a UTF8-aware
    * version of [substr_replace](http://php.net/substr_replace).
    *
    *     $str = OB_Utf8->substr_replace($str, $replacement, $offset);
    *
    * @author  Harry Fuecks <hfuecks@gmail.com>
    * @param   string   input string
    * @param   string   replacement string
    * @param   integer  offset
    * @return  string
    */
    public function substr_replace($str, $replacement, $offset, $length = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_substr_replace($str, $replacement, $offset, $length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes a UTF-8 string lowercase. This is a UTF8-aware version
    * of [strtolower](http://php.net/strtolower).
    *
    *     $str = OB_Utf8->strtolower($str);
    *
    * @author  Andreas Gohr <andi@splitbrain.org>
    * @author  Ersin Güvenç
    * @param   string   mixed case string
    * @return  string
    * @uses    UTF8::$server_utf8
    */
    public function strtolower($str)
    {
        if (self::$server_utf8)
        {
            return mb_strtolower($str, config('charset')); 
        }

        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 
            
            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        if(strpos($str, 'İ') !== FALSE)  // İ - i problem in just one Turkish Character.
        {
            $str = str_replace('İ', 'i', $str);
        }
        
        return utf8_strtolower($str);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Makes a UTF-8 string uppercase. This is a UTF8-aware version
    * of [strtoupper](http://php.net/strtoupper).
    *
    * @author  Andreas Gohr <andi@splitbrain.org>
    * @param   string   mixed case string
    * @return  string
    * @uses    UTF8::$server_utf8
    * @uses    Kohana::$charset
    */
    public function strtoupper($str)
    {
        if (self::$server_utf8)
        {
            return mb_strtoupper($str, config('charset'));
        }

        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_strtoupper($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes a UTF-8 string's first character uppercase. This is a UTF8-aware
    * version of [ucfirst](http://php.net/ucfirst).
    *
    *     $str = OB_Utf8->ucfirst($str);
    *
    * @author  Harry Fuecks <hfuecks@gmail.com>
    * @param   string   mixed case string
    * @return  string
    */
    public function ucfirst($str)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 
            
            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_ucfirst($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Makes the first character of every word in a UTF-8 string uppercase.
    * This is a UTF8-aware version of [ucwords](http://php.net/ucwords).
    *
    *     $str = OB_Utf8->ucwords($str);
    *
    * @param   string   mixed case string
    * @return  string
    * @uses    UTF8::$server_utf8
    */
    public function ucwords($str)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        if(strpos($str, 'i') === 0)  // i - I problem in Turkish Characters .
        {
            $str = 'İ'. $this->substr($str, 1);
        }
        
        return utf8_ucwords($str);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Case-insensitive UTF-8 string comparison. This is a UTF8-aware version
    * of [strcasecmp](http://php.net/strcasecmp).
    *
    *     $compare = OB_Utf8->strcasecmp($str1, $str2);
    *
    * @param   string   string to compare
    * @param   string   string to compare
    * @return  integer  less than 0 if str1 is less than str2
    * @return  integer  greater than 0 if str1 is greater than str2
    * @return  integer  0 if they are equal
    */
    public function strcasecmp($str1, $str2)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_strcasecmp($str1, $str2);
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
    public function str_ireplace($search, $replace, $str, & $count = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_str_ireplace($search, $replace, $str, $count);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Case-insenstive UTF-8 version of strstr. Returns all of input string
    * from the first occurrence of needle to the end. This is a UTF8-aware
    * version of [stristr](http://php.net/stristr).
    *
    *     $found = OB_Utf8->stristr($str, $search);
    *
    * @param   string  input string
    * @param   string  needle
    * @return  string  matched substring if found
    * @return  FALSE   if the substring was not found
    */
    public function stristr($str, $search)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_stristr($str, $search);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds the length of the initial segment matching mask. This is a
    * UTF8-aware version of [strspn](http://php.net/strspn).
    *
    *     $found = OB_Utf8->strspn($str, $mask);
    *
    * @param   string   input string
    * @param   string   mask for search
    * @param   integer  start position of the string to examine
    * @param   integer  length of the string to examine
    * @return  integer  length of the initial segment that contains characters in the mask
    */
    public function strspn($str, $mask, $offset = NULL, $length = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_strspn($str, $mask, $offset, $length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Finds the length of the initial segment not matching mask. This is a
    * UTF8-aware version of [strcspn](http://php.net/strcspn).
    *
    *     $found = OB_Utf8->strcspn($str, $mask);
    *
    * @param   string   input string
    * @param   string   mask for search
    * @param   integer  start position of the string to examine
    * @param   integer  length of the string to examine
    * @return  integer  length of the initial segment that contains characters not in the mask
    */
    public function strcspn($str, $mask, $offset = NULL, $length = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_strcspn($str, $mask, $offset, $length);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Pads a UTF-8 string to a certain length with another string. This is a
    * UTF8-aware version of [str_pad](http://php.net/str_pad).
    *
    *     $str = OB_Utf8->str_pad($str, $length);
    *
    * @param   string   input string
    * @param   integer  desired string length after padding
    * @param   string   string to use as padding
    * @param   string   padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
    * @return  string
    */
    public function str_pad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_str_pad($str, $final_str_length, $pad_str, $pad_type);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Converts a UTF-8 string to an array. This is a UTF8-aware version of
    * [str_split](http://php.net/str_split).
    *
    *     $array = OB_Utf8->str_split($str);
    *
    * @param   string   input string
    * @param   integer  maximum length of each chunk
    * @return  array
    */
    public function str_split($str, $split_length = 1)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 
            
            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_str_split($str, $split_length);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
    *
    *     $str = OB_Utf8->strrev($str);
    *
    * @param   string   string to be reversed
    * @return  string
    */
    public function strrev($str)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_strrev($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning and
    * end of a string. This is a UTF8-aware version of [trim](http://php.net/trim).
    *
    *     $str = OB_Utf8->trim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function trim($str, $charlist = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_trim($str, $charlist);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the beginning of
    * a string. This is a UTF8-aware version of [ltrim](http://php.net/ltrim).
    *
    *     $str = OB_Utf8->ltrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function ltrim($str, $charlist = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_ltrim($str, $charlist);
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strips whitespace (or other UTF-8 characters) from the end of a string.
    * This is a UTF8-aware version of [rtrim](http://php.net/rtrim).
    *
    *     $str = OB_Utf8->rtrim($str);
    *
    * @param   string   input string
    * @param   string   string of characters to remove
    * @return  string
    */
    public function rtrim($str, $charlist = NULL)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_rtrim($str, $charlist);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns the unicode ordinal for a character. This is a UTF8-aware
    * version of [ord](http://php.net/ord).
    *
    *     $digit = OB_Utf8->ord($character);
    *
    * @param   string   UTF-8 encoded character
    * @return  integer
    */
    public function ord($chr)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 

            self::$called[__FUNCTION__] = TRUE;  // Function has been called
        }

        return utf8_ord($chr);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
    * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
    * Occurrences of the BOM are ignored. Surrogates are not allowed.
    *
    *     $array = OB_Utf8->to_unicode($str);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
    *
    * @param   string  UTF-8 encoded string
    * @return  array   unicode code points
    * @return  FALSE   if the string is invalid
    */
    public function to_unicode($str)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 
            
            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_to_unicode($str);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
    * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
    * Occurrances of the BOM are ignored. Surrogates are not allowed.
    *
    *     $str = OB_Utf8->to_unicode($array);
    *
    * The Original Code is Mozilla Communicator client code.
    * The Initial Developer of the Original Code is Netscape Communications Corporation.
    * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
    * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
    * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
    *
    * @param   array    unicode code points representing a string
    * @return  string   utf8 string of characters
    * @return  boolean  FALSE if a code point cannot be found
    */
    public function from_unicode($arr)
    {
        if ( ! isset(self::$called[__FUNCTION__]))
        {
            require BASE .'helpers'. DS .'drivers'. DS .'utf8'. DS . __FUNCTION__ .EXT; 
            
            self::$called[__FUNCTION__] = TRUE; // Function has been called
        }

        return utf8_from_unicode($arr);
    }

}

/* End of file Utf8.php */
/* Location: ./obullo/libraries/Utf8.php */