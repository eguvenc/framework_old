<?php
namespace Utf8\Src;

Class Str_Split {

    // ------------------------------------------------------------------------
    
    /**
     * UTF8 str_split
     *
     * @access  private
     * @param string $str
     * @param int $split_length
     * @return string
     */
    function start($str, $split_length = 1)
    {
        $utf8 = new \Utf8(false);
        
        $split_length = (int) $split_length;

        if($utf8->isAscii($str))
        {
            return str_split($str, $split_length);
        }

        if($split_length < 1)
        {
            return false;
        }

        if($utf8->strlen($str) <= $split_length)
        {
           return array($str);
        }
                
        preg_match_all('/.{'.$split_length.'}|[^\x00]{1,'.$split_length.'}$/us', $str, $matches);

        return $matches[0];
    }

}

/* End of file str_split.php */
/* Location: ./packages/utf8/releases/0.0.1/src/str_split.php */