<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {  
        setVar('var', 'Hello, this is my first Obullo page.');
        setVar('name', 'Obullo');
        
        view('hello.world');
    }
}

/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */