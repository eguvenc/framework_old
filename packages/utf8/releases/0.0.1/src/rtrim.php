<?php
namespace Utf8\Src;

Class Rtrim {
    
    // ------------------------------------------------------------------------

    /**
     * UTF8 rtrim
     *
     * @access  private
     * @param type $str
     * @param type $charlist
     */
    function start($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return rtrim($str);
        }	

        $utf8 = new \Utf8(false);
                
        if ($utf8->isAscii($charlist))
        {
            return rtrim($str, $charlist);   
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/['.$charlist.']++$/uD', '', $str);
    }
}

/* End of file rtrim.php */
/* Location: ./ob/utf8/releases/0.0.1/src/rtrim.php */