<?php

Class Hmvc_Welcome extends Controller {
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {  
        view('hmvc_welcome', function(){

            $response_a = Request::get('tutorials/hmvc_welcome/test/1/2/3');
            $response_b = Request::get('tutorials/hmvc_welcome/test/4/5/6');

            $this->set('response_a', $response_a);
            $this->set('response_b', $response_b);
            $this->set('name', 'Obullo');
        });
    }
    
    function test($arg1 = '', $arg2 = '', $arg3 = '')
    {
        echo '<pre>Response: '.$arg1 .' - '.$arg2. ' - '.$arg3.'</pre>';   
    }
}

/* End of file hmvc_welcome.php */
/* Location: .modules/tutorials/controller/hmvc_welcome.php */