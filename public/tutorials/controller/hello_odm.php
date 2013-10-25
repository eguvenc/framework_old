<?php

Class Hello_Odm extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Model('user', 'users');
    }

    function index()
    {        
        view('hello_odm');
    }   
    
    function doPost()
    {
        $this->user->email    = Get::post('email');
        $this->user->password = Get::post('password');

        $this->user->func('checkuser', function($username){
            if(strlen($username) > 10)
            {
                $this->setMessage('checkuser', 'Username must be less than 10 characters.');
                return false;
            }
            return true;
        });
        
        $this->user->func('save',function() {
            if ($this->isValid())
            {
                $this->password = md5($this->values('password'));
                $this->db->insert('users', $this);
                return true;
            }
            return false;
        });

        if($this->user->save())
        {
            Sess::setFlash('notice', 'User saved successfully.');
            Url::redirect('tutorials/hello_odm');
        }

        view('hello_odm');
    }
}

/* End of file hello_odm.php */
/* Location: .modules/tutorials/controller/hello_odm.php */