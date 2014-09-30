<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

    /**
    * Anchor Link - Pop-up version
    *
    * Creates an anchor based on the local URL. The link
    * opens a new window based on the attributes specified.
    *
    * @access   public
    * @param    string  the URL
    * @param    string  the link title
    * @param    mixed   any attributes
    * @param    bool    switch off suffix by manually
    * @return   string
    */
    function anchorPopup($uri = '', $title = '', $attributes = false, $suffix = true)
    {
        $title = (string) $title;

        $siteUri = getInstance()->uri->getSiteUrl($uri, $suffix);

        // "?" Question mark support

        if(strpos(trim($uri,'/'), '?') === 0) // If we have question mark beginning of the  the uri // ?service_type=email&user_id=50
        {
            $siteUri = (strpos($uri, '/') === 0) ? $siteUri : trim($siteUri,'/');
        }

        // "?" Question mark support end
        
        $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? getInstance()->uri->getSiteUrl($uri, $suffix) : $uri;

        if ($title == '')
        {
            $title = $site_url;
        }

        if ($attributes === false)
        {
            return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank');\">".$title."</a>";
        }

        if ( ! is_array($attributes))
        {
            $attributes = array();
        }

        foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
        {
            $atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
            unset($attributes[$key]);
        }

        if ($attributes != '')
        {
            $attributes = getInstance()->url->_parseAttributes($attributes);
        }

        return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '".getInstance()->url->_parseAttributes($atts, true)."');\"$attributes>".$title."</a>";
    }

}