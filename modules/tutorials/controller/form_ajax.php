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

        $this->user->func('save',function() { 
            return $this->isValid();
        });

        $this->user->save();

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json;charset=UTF-8');

        echo json_encode($this->user->output());
    }
}

/* End of file form_ajax.php */
/* Location: .modules/tutorials/controller/form_ajax.php */