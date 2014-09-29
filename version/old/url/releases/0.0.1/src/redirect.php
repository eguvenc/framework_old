<?php
namespace Url\Src {

    // ------------------------------------------------------------------------

    /**
    * Header Redirect
    *
    * Header redirect in two flavors
    * For very fine grained control over headers, you could use the Output
    * Library's setHeader() function.
    *
    * @access   public
    * @param    string    the URL
    * @param    string    the method: location or refresh[param]
    * @version  0.1       added sharp support and suffix parameter
    * @return   string
    */
    function redirect($uri = '', $method = 'location', $http_response_code = 302, $suffix = true)
    {
        if ( ! preg_match('#^https?://#i', $uri))
        {
            $sharp = false;
            if(strpos($uri, '#') > 0) // ' # ' sharp support for urls. ( Obullo changes )..
            {
                $sharp_uri = explode('#', $uri);
                $uri       = $sharp_uri[0];
                $sharp     = true;
            }

            $uri = getInstance()->uri->getSiteUrl($uri, $suffix);

            if($sharp == true AND isset($sharp_uri[1]))
            {
                $uri = $uri.'#'.$sharp_uri[1];  // Obullo changes..
            }
        }

        if(strpos($method, '['))    // Obullo changes.. refresh parameter ..
        {
            $index = explode('[', $method);
            $param = str_replace(']', '', $index[1]);

            header("Refresh:$param;url=".$uri);
            return;
        }
        
        switch($method)
        {
            case 'refresh'    : header("Refresh:0;url=".$uri);
                break;
            default           : header("Location: ".$uri, true, $http_response_code);
                break;
        }
        exit;
    }

}