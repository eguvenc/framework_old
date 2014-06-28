<?php
namespace Text\Src {

    /**
    * Word Limiter
    *
    * Limits a string to X number of words.
    *
    * @access	public
    * @param	string
    * @param	integer
    * @param	string	the end character. Usually an ellipsis
    * @return	string
    */ 
    function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '')
        {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
        }

        return rtrim($matches[0]).$end_char;
    }

}