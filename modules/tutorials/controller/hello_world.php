<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {
        setVar('name', 'Obullo');

        // echo Request::get('get', 'tutorials/hmvc_welcome/test/1/2/3');

        view('hello.world');
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */