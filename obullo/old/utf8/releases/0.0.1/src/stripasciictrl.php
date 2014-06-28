<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Strips out device control codes in the ASCII range.
    *
    *     $str = $this->utf8->stripAsciiCtrl($str);
    *
    * @param   string  string to clean
    * @return  string
    */
    function stripAsciiCtrl($str)
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
    }

}

/* End of file stripasciictrl.php */
/* Location: ./packages/utf8/releases/0.0.1/src/stripasciictrl.php */