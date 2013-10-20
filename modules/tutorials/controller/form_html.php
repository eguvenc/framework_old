<?php

Class Form_Html extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Model('user', 'users');
    }

    function index()
    {        
        view('form_html');
    }   
    
    function doPost()
    {
        // $this->user->email = Get::post('email');
        // $this->user->password = Get::post('password');

        /*
        $this->user->func('checkuser', function(){
            $this->setMessage('checkuser', 'error in field');
            return false;
        });
        */

        // echo key(class_uses($this->user));
        // print_r($this->user->getAllMethods());
        // exit;

        $this->user->func('save',function() {

            if ($this->isValid())
            {
                // $this->password = md5($this->values('password'));

                // $this->db->insert('users', $this);
                return true;
            }

            return false;
        });

        
        $errors  = array();
        $users[] = array('email' => 'hasan@gmail', 'password' => '');
        $users[] = array('email' => 'ersin@gmail.com', 'password' => '');

        foreach ($users as $row)
        {
            $this->user->clear();
            $this->user->email = $row['email'];
            $this->user->password = $row['password'];

            if($this->user->save())
            {

            } else {
                $errors[] = $this->user->errors();
            }

        }

        print_r($errors);

/*
        if($this->user->save())
        {
            Sess::setFlash('notice', 'User saved successfully.');

            Url::redirect('tutorials/form_html');
        }
*/
        // print_r($this->user->errors());
        // var_dump($this->user);

        view('form_html');
    }
}

/* End of file form.php */
/* Location: .modules/tutorials/controller/form.php */