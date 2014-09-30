<?php

/**
 * A port of [phputf8]( to a unified set of files. Provides multi-byte aware replacement string functions.
 * For UTF-8 support to work correctly, the following requirements must be met:
 *
 * - PCRE needs to be compiled with UTF-8 support (--enable-utf8)
 * - Support for [Unicode properties](http://php.net/manual/reference.pcre.pattern.modifiers.php)
 *   is highly recommended (--enable-unicode-properties)
 * - UTF-8 conversion will be much more reliable if the [iconv extension](http://php.net/iconv) is loaded
 * - The [mbstring extension](http://php.net/mbstring) is highly recommended, but must not be overloading string functions
 *
 * [!!] This file is licensed differently from the rest of Obullo. 
 * As a port of [phputf8](http://phputf8.sourceforge.net/), this file is released under the LGPL.
 * Originally borrowed from Kohana Framework, Harry Fuecks and Andreas Gohr.
 * 
 * @package    packages
 * @link       http://phputf8.sourceforge.net/)
 * @license    Utf8 library licenced under the http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */

Class Utf8 {

    public static $instance;

    /**
    * Constructor - Set server utf8 extension.
    * Determine if this server supports UTF-8 natively
    *
    * @return void
    */
    public function __construct()
    {
        global $logger;

        if( ! extension_loaded('mbstring'))
        {
            throw new Exception('Mbstring extension not loaded.');
        }
        
        if( ! isset(getInstance()->utf8))
        {
            getInstance()->utf8 = $this; // Available it in the contoller $this->utf8->method();
        }

        $logger->debug('Utf8 Class Initialized');
    }
    
    // --------------------------------------------------------------------

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
    
    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Utf8\Src\\'.$method))
        {
            require PACKAGES .'utf8'. DS .'releases'. DS .$packages['dependencies']['utf8']['version']. DS .'src'. DS .mb_strtolower($method). EXT;
        }

        return call_user_func_array('Utf8\Src\\'.$method, $arguments);
    }

}

/* End of file utf8.php */
/* Location: ./packages/utf8/releases/0.0.1/utf8.php */