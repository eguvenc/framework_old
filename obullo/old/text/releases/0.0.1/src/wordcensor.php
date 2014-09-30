<?php
namespace Text\Src {

    // ------------------------------------------------------------------------

    /**
    * Word Censoring Function
    *
    * Supply a string and an array of disallowed words and any
    * matched words will be converted to #### or to the replacement
    * word you've submitted.
    *
    * @access	public
    * @param	string	the text string
    * @param	string	the array of censoered words
    * @param	string	the optional replacement value
    * @return	string
    */
    function wordCensor($str, $censored, $replacement = '')
    {
        if ( ! is_array($censored))
    	{
            return $str;
    	}
        
        $str = ' '.$str.' ';

        // \w, \b and a few others do not match on a unicode character
        // set for performance reasons. As a result words like über
        // will not match on a word boundary. Instead, we'll assume that
        // a bad word will be bookended by any of these characters.
        $delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

        foreach ($censored as $badword)
        {
            if ($replacement != '')
            {
                $str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
            }
            else
            {
                $str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
            }
        }

        return trim($str);
    }
    
}