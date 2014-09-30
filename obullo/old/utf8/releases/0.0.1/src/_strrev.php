<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
    *
    *     $str = $this->utf8->_strrev($str);
    *
    * @param   string   string to be reversed
    * @return  string
    */

    // ------------------------------------------------------------------------

    /**
    * UTF8 strrev
    *
    * @access  public
    * @param   string $str
    * @return  string
    */
    function _strrev($str)
    {
        $utf8 = getInstance()->utf8;
        
        if($utf8->isAscii($str))
        {
            return strrev($str);
        }

        preg_match_all('/./us', $str, $matches);
        
        return implode('', array_reverse($matches[0]));
    }

}

/* End of file _strrev.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strrev.php */