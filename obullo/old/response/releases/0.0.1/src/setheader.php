<?php
namespace Response\Src {

    // --------------------------------------------------------------------

    /**
    * Set Header
    *
    * Lets you set a server header which will be outputted with the final display.
    *
    * Note:  If a file is cached, headers will not be sent.  We need to figure out
    * how to permit header data to be saved with the cache data...
    *
    * @access   public
    * @param    string
    * @return   void
    */    
    function setHeader($header, $replace = true)
    {
        global $response;

        // If zlib.output_compression is enabled it will compress the output,
        // but it will not modify the content-length header to compensate for
        // the reduction, causing the browser to hang waiting for more data.
        // We'll just skip content-length in those cases.

        if (@ini_get('zlib.output_compression') AND strncasecmp($header, 'content-length', 14) == 0)
        {
            return;
        }
        
        $response->headers[] = array($header, $replace);
    }
}