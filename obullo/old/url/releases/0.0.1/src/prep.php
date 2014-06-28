<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

    /**
    * Prep URL
    *
    * Simply adds the http:// part if missing
    *
    * @access	public
    * @param	string	the URL
    * @return	string
    */
    function prep($str = '')
    {
        if ($str == 'http://' OR $str == '')
        {
            return '';
        }

        if ( ! parse_url($str, PHP_URL_SCHEME))
        {
            $str = 'http://'.$str;
        }

        return $str;
    }

}