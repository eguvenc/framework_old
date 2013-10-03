<?php

Class Form_Html extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        Sess::start();
    }         

    function index()
    {        
        view('form_html');
    }
    
    function doPost()
    {
        $user = new Models\User();
        
        $user->user_password = Get::post('user_password'); 
        $user->user_email    = Get::post('user_email');
        
        if($user->save())
        {
            Sess::setFlash('notice', 'Form saved succesfully');
            Url::redirect('tutorials/form');
        }

        setVar('user', $user);
        view('form_html');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */