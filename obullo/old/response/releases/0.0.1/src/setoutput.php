<?php
namespace Response\Src {

    // --------------------------------------------------------------------
    
    /**
    * Set Output
    *
    * Sets the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    function setOutput($output)
    {   
        global $response;

        $response->final_output = $output;
    }
}