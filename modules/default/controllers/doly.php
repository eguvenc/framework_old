<?php

/**
* Your copy paste Controller.
*/
Class Doly extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }                               

    public function index()
    {
        echo 'Default module controller works !!';
       
        echo br(2);
        
        log_me('debug', 'Default module example log message saved to /MODULES/default/core/logs/ folder !');
        
        echo anchor('default/subdir', 'Try Subfolder !');
    }
    
}

/* End of file doly.php */
/* Location: .modules/default/controllers/doly.php */
