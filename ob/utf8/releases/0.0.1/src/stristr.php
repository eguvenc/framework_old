<?php
namespace Utf8\Src;

Class Stristr {

    // ------------------------------------------------------------------------

    /**
    * UTF8 stristr
    *
    * @access  private
    * @param   string $str
    * @param   string $search
    * @return  string
    */
    function start($str, $search)
    {
        $utf8 = new \Utf8(false);
        
        if($utf8->isAscii($str) AND $utf8->isAscii($search))
        {
            return stristr($str, $search);
        }
                
        if($search == '')
        {
            return $str;
        }

        $str_lower    = $utf8->strtolower($str);
        $search_lower = $utf8->strtolower($search);

        preg_match('/^(.*?)'.preg_quote($search_lower, '/').'/s', $str_lower, $matches);

        if(isset($matches[1]))
        {
            return substr($str, strlen($matches[1]));
        } 

        return false;
    }   
}

/* End of file stristr.php */
/* Location: ./ob/utf8/releases/0.0.1/src/stristr.php */