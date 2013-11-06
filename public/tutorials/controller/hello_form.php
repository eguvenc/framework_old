<?php

Class Hello_Form extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();

        new Model('user', 'users');
        new uForm();

        $this->uform->func(function(){

            $this->open('/tutorials/hello_form', array('method' => 'post'));

                $this->label('Password');
                $this->error('password');
                $this->password('password', '', " id='password' ");

                $this->break();

                $this->label('Password'); 
                $this->error('confirm_password');
                $this->password('confirm_password', '', " id='confirm' ");

            $this->close();

        }, 'default');

        /*
                $this->formVar = function(){
            echo Form::open('/tutorials/hello_form', array('method' => 'post'), 'default');

                    echo Form::label('Password'); 
                    echo Form::error('password');
                    echo Form::password('password', '', " id='password' ");
                    
                    echo Form::break();

                    echo Form::label('Password'); 
                    echo Form::error('confirm_password');
                    echo Form::password('confirm_password', '', " id='confirm' ");
                    
            echo Form::close();
        };         

         */
        
    }

    function index()
    {        
        view('hello_form',function() {
            $this->set('form_html', $this->formVar);
        });
    }
    
    function doPost()
    {
        $this->user->email    = Get::post('email');
        $this->user->password = Get::post('password');

        $this->user->setRule('confirm_password', array('rules' => 'required|matches(password)'));
        $this->user->setRule('agreement', array('label' => 'User Agreement', 'rules' => 'integer|required'));
       
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
            Url::redirect('tutorials/hello_form');
        }

        view('hello_form', function() {
            $this->set('form_html', $this->formVar);
        });
    }

}


/* End of file hello_world.php */
/* Location: .modules/tutorials/controller/hello_world.php */