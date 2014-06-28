<?php

/**
* Stringo Class
*
* @package       packages
* @subpackage    string
* @category      string
* @link
*/

Class Stringo {
    
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->stringo))
        {
            getInstance()->stringo = $this; // Make available it in the controller $this->stringo->method();
        }

        $logger->debug('Stringo Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
    * Strip Quotes
    *
    * Removes single and double quotes from a string
    *
    * @access	public
    * @param	string
    * @return	string
    */
    public function stripQuotes($str)
    {
        return str_replace(array('"', "'"), '', $str);
    }

    // ------------------------------------------------------------------------

    /**
    * Quotes to Entities
    *
    * Converts single and double quotes to entities
    *
    * @access	public
    * @param	string
    * @return	string
    */
    public function quotesToEntities($str)
    {
        return str_replace(array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $str);
    }

    // ------------------------------------------------------------------------

    /**
    * Reduce Double Slashes
    *
    * Converts double slashes in a string to a single slash,
    * except those found in http://
    *
    * http://www.some-site.com//index.php
    *
    * becomes:
    *
    * http://www.some-site.com/index.php
    *
    * @access	public
    * @param	string
    * @return	string
    */
    public function reduceDoubleSlashes($str)
    {
        return preg_replace("#(^|[^:])//+#", "\\1/", $str);
    }

    // ------------------------------------------------------------------------

    /**
    * Reduce Multiples
    *
    * Reduces multiple instances of a particular character.  Example:
    *
    * Fred, Bill,, Joe, Jimmy
    *
    * becomes:
    *
    * Fred, Bill, Joe, Jimmy
    *
    * @access	public
    * @param	string
    * @param	string	the character you wish to reduce
    * @param	bool	true/false - whether to trim the character from the beginning/end
    * @return	string
    */
    public function reduceMultiples($str, $character = ',', $trim = false)
    {
        $str = preg_replace('#'.preg_quote($character, '#').'{2,}#', $character, $str);

        if ($trim === true)
        {
            $str = trim($str, $character);
        }

        return $str;
    }
    
    // ------------------------------------------------------------------------

    /**
    * Create a Random String
    *
    * Useful for generating passwords or hashes.
    *
    * @access	public
    * @param	string 	type of random string.  Options: alunum, numeric, nozero, unique
    * @param	integer	number of characters
    * @return	string
    */
    public function random($type = 'alnum', $len = 8)
    {        
        switch($type)
        {
            case 'basic'    : return mt_rand();
              break;
            case 'alnum'    :
            case 'alnum_lower' :
            case 'alnum_upper' :
            case 'numeric'  :
            case 'nozero'   :
            case 'alpha'    :
        
                    switch ($type)
                    {
                        case 'alpha'        :    $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'alnum'        :    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'alnum_lower'  :    $pool = '123456789abcdefghijklmnopqrstuvwxyz';
                            break;
                        case 'alnum_upper'  :    $pool = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'numeric'      :    $pool = '0123456789';
                            break;
                        case 'nozero'       :    $pool = '123456789';
                            break;
                    }

                    $str = '';
                    for ($i=0; $i < $len; $i++)
                    {
                        $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                    }
                    return $str;
              break;
            case 'unique'    : 
            case 'md5'       : 
                
                return md5(uniqid(mt_rand()));
              break;
            case 'encrypt'    : 
            case 'sha1'       : 
                        return sha1(uniqid(mt_rand(), true));
              break;
        }
    }
    
    // ------------------------------------------------------------------------

    /**
    * Alternator
    *
    * Allows strings to be alternated.  See docs...
    *
    * @access	public
    * @param	string (as many parameters as needed)
    * @return	string
    */
    public function alternator()
    {
        static $i;	

        if (func_num_args() == 0)
        {
            $i = 0;
            return '';
        }

        $args = func_get_args();

        return $args[($i++ % count($args))];
    }
    
    // ------------------------------------------------------------------------

    /**
    * Repeater function
    *
    * @access	public
    * @param	string
    * @param	integer	number of repeats
    * @return	string
    */
    public function repeater($data, $num = 1)
    {
        return (($num > 0) ? str_repeat($data, $num) : '');
    }
    
}

/* End of file stringo.php */
/* Location: ./packages/stringo/releases/0.0.1/stringo.php */