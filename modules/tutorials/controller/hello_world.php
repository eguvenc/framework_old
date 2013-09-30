<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {  
        // Make::var('var', 'Hello, this is my first Obullo page.');
        Make::setVar('name', 'Obullo');
        
        // echo Request::get('get', 'tutorials/hmvc_welcome/test/1/2/3');

        Make::view('hello.world');
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */