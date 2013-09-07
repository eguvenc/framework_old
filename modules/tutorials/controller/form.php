<?php

Class Form extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new form\start();
        new sess\start();
    }         

    function index()
    {        
        vi\view('form');
    }
    
    function post()
    {   
        $user = new Models\User();
        $user->user_password = i\post('user_password');
        $user->user_email    = i\post('user_email');
        
        if($user->save())
        {
            sess\setFlash('notice', 'Form saved succesfully');
            url\redirect('tutorials/form');
        }

        vi\setVar('user', $user);
        vi\view('form');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */