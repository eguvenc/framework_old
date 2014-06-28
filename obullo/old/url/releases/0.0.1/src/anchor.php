<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

    /**
    * Anchor Link
    *
    * Creates an anchor based on the local URL.
    *
    * @access    public
    * @param     string    the URL
    * @param     string    the link title
    * @param     mixed     any attributes
    * @param     bool      switch off suffix by manually
    * @return    string
    */
    function anchor($uri = '', $title = '', $attributes = '', $suffix = true)
    {
        $title = (string) $title;
        $sharp = false;

        $siteUri = getInstance()->uri->getSiteUrl($uri, $suffix);

        // ' # ' sharp support
        
        if(strpos($uri, '#') === 0)  // If we have "#" begining of the uri
        {
            return '<a href="'.trim($siteUri, '/').'"'.$attributes.'>'.$title.'</a>';
        }
        elseif(strpos($uri, '#') > 0)  
        {
            $sharp_uri = explode('#', $uri);
            $uri       = $sharp_uri[0];
            $sharp     = true;
        }

        // "?" Question mark support

        if(strpos(trim($uri,'/'), '?') === 0) // If we have question mark beginning of the  the uri // ?service_type=email&user_id=50
        {
            $siteUri = (strpos($uri, '/') === 0) ? $siteUri : trim($siteUri,'/');
        }

        // "?" Question mark support end
        
        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? $siteUri : $uri;

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes != '')
        {
            $attributes = getInstance()->url->_parseAttributes($attributes);
        }

        if($sharp == true AND isset($sharp_uri[1]))
        {
            $site_url = $site_url.'#'.$sharp_uri[1];  // sharp support
        }

        return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
    }

}