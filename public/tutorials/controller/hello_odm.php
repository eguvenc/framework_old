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

        $this->user->setRule('confirm_password', array('rules' => 'required|matches(password)'));
        $this->user->setRule('agreement', array('label' => 'User Agreement', 'rules' => '_int(1)|required'));
       
        $this->user->func('save', function() {
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