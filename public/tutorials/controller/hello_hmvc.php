<?php

Class Hello_hmvc extends Controller {
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {  
        view('hello_hmvc', function(){

            $response_a = Request::get('tutorials/hello_hmvc/test/1/2/3');
            $response_b = Request::get('tutorials/hello_hmvc/test/4/5/6');

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

/* End of file hello_hmvc.php */
/* Location: .public/tutorials/controller/hello_hmvc.php */