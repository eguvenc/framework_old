<?php

Class Hello_World extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {  
        vi\setVar('var', 'Hello, this is my first Obullo page.');
        vi\setVar('name', 'Obullo');
        
        vi\view('hello.world');
    }
}

/* End of file hello_world.php */
/* Location: .mods/tutorials/controller/hello_world.php */