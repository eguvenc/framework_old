<?php

Class Hello_Scheme extends Controller {    
                                      
    function __construct()
    {
    	parent::__construct();
    }

    function index()
    { 
        tpl('default', function(){
            $this->set('title', 'Hello Scheme');
            $this->set('name', 'Obullo');

            $this->scheme('general', 'hello_scheme');
        });
    }
}

/* End of file hello_scheme.php */
/* Location: .public/tutorials/controller/hello_scheme.php */