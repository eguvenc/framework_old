<?php
namespace Response\Src {

    //----------------------------------------------------------------------- 

    /**
    * 404 Page Not Found Handler
    *
    * @access   private
    * @param    string
    * @return   string
    */
    function show404($page = '')
    {
        global $logger, $response;

        $logger->error('404 Page Not Found --> '.$page);

        echo $response->showHttpError('404 Page Not Found', $page, '404', 404);
        exit();
    }

}