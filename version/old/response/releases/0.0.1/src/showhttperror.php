<?php
namespace Response\Src {

    // --------------------------------------------------------------------

    /**
    * General Http Errors
    *
    * @access   private
    * @param    string    the heading
    * @param    string    the message
    * @param    string    the template name
    * @param    int       header status code
    * @return   string
    */
    function showHttpError($heading, $message, $template = 'general', $statusCode = 500)
    {
        global $response;

        $response->setHttpResponse($statusCode);

        $message = implode('<br />', ( ! is_array($message)) ? array($message) : $message);

        if(defined('STDIN'))  // If Command Line Request
        {
            return '['.$heading.']: The url ' .$message. ' you requested was not found.'."\n";
        }
        
        ob_start();
        
        include(APP .'errors'. DS .$template. EXT);

        $buffer = ob_get_clean();
        
        return $buffer;
    }

}