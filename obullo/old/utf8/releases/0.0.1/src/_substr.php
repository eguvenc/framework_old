<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Returns part of a UTF-8 string. This is a UTF8-aware version
    * of [substr](http://php.net/substr).
    *
    *     $sub = $this->utf8->substr($str, $offset);
    *
    * @param   string   input string
    * @param   integer  offset
    * @param   integer  length limit
    * @return  string
    * @uses    getConfig();
    */
    function _substr($str, $offset, $length = null)
    {
        global $config;

        return ($length === null) 
            ? mb_substr($str, $offset, mb_strlen($str), $config['charset']) 
                    : mb_substr($str, $offset, $length, $config['charset']);

    }

}

/* End of file _substr.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_substr.php */