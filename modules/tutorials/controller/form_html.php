<?php

Class Form_Html extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Model('user');
    }

    function index()
    {        
        view('form_html');
    }
    
    function doPost()
    {
        $this->user->email = Get::post('email');
        $this->user->password = Get::post('password');

        $this->user->func('save',function() {    // transaction ları Trait içerisine koy.
            // $this->setTransaction(false);

            if ($this->validate())
            {
                $this->password = md5($this->values('password'));
                
                return $this->db->insert('users', $this);
            }

            return false;
        });

        if($this->user->save())
        {
            Sess::setFlash('notice', 'User saved successfully.');
            Url::redirect('tutorials/form_html');
        }

        // print_r($this->user->errors());
        // var_dump($this->user);

        // $this->user->messages(); Transaction error varsa yada genel bir hata buradan gelsin.

        view('form_html');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */