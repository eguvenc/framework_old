<?php

Class Form_Ajax extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Model('user');
    }         

    function index()
    {        
        view('form_ajax');
    }
    
    function doPost()
    {   
        $this->user->email = Get::post('email');
        $this->user->password = Get::post('password');

        $this->user->func('save',function() {    // transaction ları Trait içerisine koy.
            return $this->validate();
        });

        $this->user->save();

        echo Response::json($this->user->output());
    }
}

/* End of file form_ajax.php */
/* Location: .modules/tutorials/controller/form_ajax.php */