<?php
namespace Text\Src {

    // ------------------------------------------------------------------------

    /**
    * Phrase Highlighter
    *
    * Highlights a phrase within a text string
    *
    * @access	public
    * @param	string	the text string
    * @param	string	the phrase you'd like to highlight
    * @param	string	the openging tag to precede the phrase with
    * @param	string	the closing tag to end the phrase with
    * @return	string
    */
    function highlightPhrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
    {
        if ($str == '')
        {
            return '';
        }

        if ($phrase != '')
        {
            return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
        }

        return $str;
    }

}