<?php

Class Hmvc_Welcome extends Controller {
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {        
        new request\start();

        $response_a = request\get('tutorials/hmvc_welcome/test/1/2/3');
        $response_b = request\get('tutorials/hmvc_welcome/test/4/5/6');
        
        vi\setVar('response_a', $response_a);
        vi\setVar('response_b', $response_b);
        vi\setVar('name', 'Obullo');
        
        vi\view('hmvc.welcome');
    }
    
    function test($arg1 = '', $arg2 = '', $arg3 = '')
    {
        echo '<pre>Response: '.$arg1 .' - '.$arg2. ' - '.$arg3.'</pre>';   
    }
}

/* End of file hmvc_welcome.php */
/* Location: .mods/tutorials/controller/hmvc_welcome.php */