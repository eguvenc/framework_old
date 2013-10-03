<?php

Class Form_Ajax extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {        
        view('form_ajax');
    }
    
    function doPost()
    {   
        $user = new Models\User();
        
        $user->user_password = Get::post('user_password');
        $user->user_email    = Get::post('user_email');
  
        if($user->save())
        {
            echo Form_Json::success($user);
            return;
        } 
        else
        {
            echo Form_Json::error($user);
            return;
        }

        view('form_ajax');
    }
}

/* End of file form_ajax.php */
/* Location: .modules/tutorials/controller/form_ajax.php */