<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Converts a UTF-8 string to an array. This is a UTF8-aware version of
    * [str_split](http://php.net/str_split).
    *
    *     $array = $this->utf8->_str_split($str);
    *
    * @param   string   input string
    * @param   integer  maximum length of each chunk
    * @return  array
    */

    /**
     * UTF8 str_split
     *
     * @access  public
     * @param string $str
     * @param int $split_length
     * @return string
     */
    function _str_split($str, $split_length = 1)
    {
        $utf8 = getInstance()->utf8;
        
        $split_length = (int) $split_length;

        if($utf8->isAscii($str))
        {
            return str_split($str, $split_length);
        }

        if($split_length < 1)
        {
            return false;
        }

        if($utf8->_strlen($str) <= $split_length)
        {
           return array($str);
        }
                
        preg_match_all('/.{'.$split_length.'}|[^\x00]{1,'.$split_length.'}$/us', $str, $matches);

        return $matches[0];
    }

}

/* End of file _str_split.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_str_split.php */