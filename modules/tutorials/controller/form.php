<?php

Class Form extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index()
    {        
        view('form');
    }
    
    function post()
    {
        $user = new Models\User();
        $user->user_password = post('user_password'); 
        $user->user_email    = post('user_email');
        
        if($user->save())
        {
            Sess::setFlash('notice', 'Form saved succesfully');
            redirect('tutorials/form');
        }

        setVar('user', $user);
        view('form');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */