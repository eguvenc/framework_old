<?php
defined('CMD') or exit('Access Denied!');

Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }         
    
    public function index($arg1 = '', $arg2 = '')
    {     
        loader::helper('ob/url');
        
        echo 'Module: '.module()."\n";
        echo 'Hello World !'."\n";
        echo 'Argument 1: '.$arg1."\n";
        echo 'Argument 2: '.$arg2."\n";
        echo 'The Start Controller Index function successfully works !'."\n";
    }
    
}

/* End of file start.php */
/* Location: .modules/welcome/tasks/start/start.php */