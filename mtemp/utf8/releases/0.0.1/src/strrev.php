<?php
namespace Utf8\Src;

Class Strrev {

    // ------------------------------------------------------------------------

    /**
    * UTF8 strrev
    *
    * @access  private
    * @param   string $str
    * @return  string
    */
    function start($str)
    {
        $utf8 = new \Utf8(false);
        
        if($utf8->isAscii($str))
        {
            return strrev($str);
        }

        preg_match_all('/./us', $str, $matches);
        
        return implode('', array_reverse($matches[0]));
    }

}

/* End of file strrev.php */
/* Location: ./packages/utf8/releases/0.0.1/src/strrev.php */