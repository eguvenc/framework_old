<?php
namespace Utf8\Src;

Class Strspn {

    // ------------------------------------------------------------------------

    /**
    * UTF8 strspn
    *
    * @access  private
    * @param   string $str
    * @param   string $mask
    * @param   int $offset
    * @param   int $length
    * @return  string
    */
    function start($str, $mask, $offset = null, $length = null)
    {
        $utf8 = new \Utf8(false);
        
        if($str == '' OR $mask == '')
        {
            return 0;
        } 

        if($utf8->isAscii($str) AND $utf8->isAscii($mask))
        {
            return ($offset === null) ? strspn($str, $mask) : (($length === null) 
                    ? strspn($str, $mask, $offset) : strspn($str, $mask, $offset, $length));
        }

        if($offset !== null OR $length !== null)
        {
            $str = $utf8->substr($str, $offset, $length);
        }

        // Escape these characters:  - [ ] . : \ ^ /
        // The . and : are escaped to prevent possible warnings about POSIX regex elements
        $mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);
        
        preg_match('/^[^'.$mask.']+/u', $str, $matches);

        return isset($matches[0]) ? $utf8->strlen($matches[0]) : 0;
    }
    
}

/* End of file strspn.php */
/* Location: ./packages/utf8/releases/0.0.1/src/strspn.php */