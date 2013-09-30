<?php

Class Form extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {        
        Vi::get('form');
    }
    
    function post()
    {
        $user = new Models\User();
        $user->user_password = Get::post('user_password'); 
        $user->user_email    = Get::post('user_email');
        
        if($user->save())
        {
            Sess::setFlash('notice', 'Form saved succesfully');
            Url::redirect('tutorials/form');
        }

        Var::setObject('user', $user);

        Vi::get('form');
        // Vi::get('form', false, 'views');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */