<?php
namespace string {
    
    /**
    * String Helper
    *
    * @package     Obullo
    * @subpackage  Helpers
    * @category    String
    * @link
    */
    Class start
    {
        function __construct()
        {
            \log\me('debug', 'String Helper Initialized.');
        }
    }
    
    // ------------------------------------------------------------------------

    /**
    * Strip Slashes
    *
    * Removes slashes contained in a string or in an array
    *
    * @access	public
    * @param	mixed	string or array
    * @return	mixed	string or array
    */
    function strip_slashes($str)
    {
        if (is_array($str))
        {	
            foreach ($str as $key => $val)
            {
                $str[$key] = strip_slashes($val);
            }
        }
        else
        {
            $str = stripslashes($str);
        }

        return $str;
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
    function strip_quotes($str)
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
    function quotes_to_entities($str)
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
    function reduce_double_slashes($str)
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
    function reduce_multiples($str, $character = ',', $trim = false)
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
    function random($type = 'alnum', $len = 8)
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
    function alternator()
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
    function repeater($data, $num = 1)
    {
        return (($num > 0) ? str_repeat($data, $num) : '');
    }
    
}

/* End of file string.php */
/* Location: ./ob/string/releases/0.0.1/string.php */