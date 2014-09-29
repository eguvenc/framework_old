<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

    /**
    * Create URL Title
    *
    * Takes a "title" string as input and creates a
    * human-friendly URL string with either a dash
    * or an underscore as the word separator.
    *
    * @access	public
    * @param	string	the string
    * @param	string	the separator: dash, or underscore
    * @return	string
    */
    function createTitle($str, $separator = 'dash', $lowercase = false)
    {
        if ($separator == 'dash')
        {
            $search		= '_';
            $replace	= '-';
        }
        else
        {
            $search		= '-';
            $replace	= '_';
        }

        $trans = array(
                        '&\#\d+?;' => '',
                        '&\S+?;'   => '',
                        '\s+'	   => $replace,
                        '[^a-z0-9\-\._]'    => '',
                        $replace.'+'        => $replace,
                        $replace.'$'        => $replace,
                        '^'.$replace        => $replace,
                        '\.+$'              => ''
                      );

        $str = strip_tags($str);

        foreach ($trans as $key => $val)
        {
            $str = preg_replace("#".$key."#i", $val, $str);
        }

        if ($lowercase === true)
        {
            $str = strtolower($str);
        }

        return trim(stripslashes($str));
    }

}